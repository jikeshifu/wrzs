<?php
declare (strict_types=1);

namespace app\command;

use app\common\device\air_switch\AirSwitchFactory;
use app\common\kg\Kg;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;

class UserIni extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('userIni')
            ->setDescription('the userIni command');
    }

    protected function execute(Input $input, Output $output)
    {
        $Beanstalkd = Kg::app()->Beanstalkd();

        while (true) {
            //读取管道队列
            $job = $Beanstalkd->watch('rrt-userIni')->reserve();
            //获取管道内容

            $data = json_decode($job->getData(), true);
            Db::startTrans();
            try {
                $member_id= $data['member_id'];

                Db::name('member')->insertGetId(['member_id' => $member_id]);
//                Db::name('member_wallet')->insertGetId(['member_id' => $member_id]);

                //查询是否有新用户注册赠送套餐
                $discount_coupons = Db::name("discount_coupons")->field('day_number,money,title,coupons_id')->where([

                    'deleted_at' => 0,
                    'new_user_status' => 1
                ])->cache(true,60)->select()->toArray();
                if ($discount_coupons) {
                    foreach ($discount_coupons as $vo) {
                        $vo['end_time'] = time()+($vo['day_number']*86400);
                        unset($vo['day_number']);
                        $vo['member_id'] = $member_id;
                        $vo['created_at'] = time();
                        Db::name("member_coupons")->insert($vo);
                    }
                }
                // 提交事务
                Db::commit();
                $Beanstalkd->delete($job);
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
              print_r( $e->getMessage());
              die;
            }


        }
        // 指令输出
        $output->writeln('userIni');
    }


}

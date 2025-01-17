<?php
declare (strict_types=1);

namespace app\command;

use app\common\device\air_switch\AirSwitchFactory;
use app\common\kg\Kg;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;

class Cabinet extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('cabinet')
            ->setDescription('the cabinet command');
    }

    protected function execute(Input $input, Output $output)
    {
        $Beanstalkd = Kg::app()->Beanstalkd();

        while (true) {
            //读取管道队列
            $job = $Beanstalkd->watch('rrt-Cabinet')->reserve();
            //获取管道内容

            $data = json_decode($job->getData(), true);
            print_r($data);
            try {
                Db::startTrans();
                //修改订单为已完成
                Db::name('order')->where(['order_id' => $data['order_id']])->update([
                    "order_status" => 3,
                    "pay_status" => 1,
                    "pay_time" => time(),
                    "pay_type" => $data['pay_type'],
                ]);


                //查询售货柜订单
                $order_cabinet = Db::name('order_cabinet')->where(['order_id' => $data['order_id']])->find();
                $goods_info = json_decode($order_cabinet['goods_info'], true);

                Db::name("cabinet_goods")->where(["goods_id" => $goods_info['goods_id']])->update([
                    "stock" => 0
                ]);


                Db::commit();
                if (!(Kg::app()->device()->cabinet($order_cabinet['device_sn'])->open($order_cabinet['cabinet_number']))) {

                    Db::name('order_cabinet')->where(['order_id' => $data['order_id']])->update([
                        "lock_status" => 1
                    ]);
                }
                $Beanstalkd->delete($job);
                print_r("执行结束");
            } catch (\Exception $e) {

                // 回滚事务
                Db::rollback();

                print_r($e->getMessage());

                die;
            }


        }
        // 指令输出
        $output->writeln('cabinet');
    }


}

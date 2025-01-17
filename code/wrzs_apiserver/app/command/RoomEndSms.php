<?php
declare (strict_types=1);

namespace app\command;


use app\common\kg\Kg;
use app\common\sms\Member;
use app\module\hardwareCloud\HardwareClout;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;

class RoomEndSms extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('roomEndSms')
            ->setDescription('the roomEndSms command');
    }

    protected function execute(Input $input, Output $output)
    {
        $Beanstalkd = Kg::app()->Beanstalkd();

        while (true) {
            //读取管道队列
            $job = $Beanstalkd->watch('rrt-roomEndSms')->reserve();

            $order_id = $job->getData();
            try {
                //查询订单房间信息
                $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();
                //判断用户结束时间减去当前时间是否大于900
                if ($order_room) {

                    if (($order_room['end_time'] - time()) > 900) {
                        //用户已经续费删除任务
                        $Beanstalkd->delete($job);
                    } else {


                        //短信通知用户续费
                        $member_wechat = Db::name('member_wechat')->where([
                            'member_id' => $order_room['member_id']])->find();

                        $room_info = json_decode($order_room['room_info'], true);
                        $device = Db::name('device')->where(['room_id' => $room_info["room_id"],"device_type"=>4])->find();
                        if ($device) {
                          HardwareClout::Horn()->Play($device["device_sn"],$device["voice"],$device["volume"]);
                        }

                        if ($member_wechat && $member_wechat['mobile']) {
                            Member::renew([$member_wechat['mobile']], [$room_info['room_name'], '15']);
                        }

                        $Beanstalkd->delete($job);
                    }
                } else {
                    $Beanstalkd->delete($job);
                }


            } catch (\Exception $e) {
                print_r($e->getMessage());
                die;
            }
        }
        // 指令输出
        $output->writeln('roomEnd');
    }


}

<?php
declare (strict_types=1);

namespace app\command;

use app\common\cache\Redis;
use app\common\device\air_switch\AirSwitchFactory;
use app\common\kg\Kg;
use app\common\sms\Manage;
use app\module\hardwareCloud\HardwareClout;
use app\server\store_admin\StoreAdmin;
use think\console\Command;
use think\console\Input;

use think\console\Output;
use think\facade\Db;

class RoomEnd extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('roomEnd')
            ->setDescription('the roomEnd command');
    }

    protected function execute(Input $input, Output $output)
    {
        $Beanstalkd = Kg::app()->Beanstalkd();

        while (true) {
            //读取管道队列
            $job = $Beanstalkd->watch('rrt-roomEnd')->reserve();

            $order_id = $job->getData();

            echo "\n任务开始" . $order_id;
            try {
                $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();
                if ($order_room) {

                    if ($order_room['room_status'] == 4) {
                        echo "订单已经取消" . $order_id . '
                    ';
                        $Beanstalkd->delete($job);
                    } else if ($order_room['end_time'] > time()) {
                        $endDelay = $order_room['end_time'] - time();
                        if ($endDelay < 0) {
                            $endDelay = 0;
                        }
                        //用户续费给任务延迟
                        $Beanstalkd->release($job, 1024, $endDelay);
                    } else {
                        //
                        echo "开始处理房间";
                        if (!$this->room($order_id, $order_room)) {
                            $Beanstalkd->delete($job);
                        }

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

    public function room($order_id, $order_room)
    {


        try {

            //修改房间状态为结束
            Db::name('order_room')->where(['order_id' => $order_id])->update(['room_status' => 3]);
            //修改房间状态为完成
            Db::name('order')->where(['order_id' => $order_id])->update([
                'order_status' => 3,
                "finish_time" => time()
            ]);
            //删除钥匙
            Db::name('room_key')->where(['order_id' => $order_id])->update(['deleted_at' => time()]);
            //查询空开设备
            $room_info = json_decode($order_room['room_info'], true);
            $device = Db::name('device')->where([
                'room_id' => $order_room['room_id'],
                'device_type' => 3,
                'deleted_at' => 0
            ])->find();
            //如果房间为4 修改房间状态
            if ($room_info['room_type'] == 4) {
                Db::name('device')->where([
                    'room_id' => $order_room['room_id'],
                ])->update(['room_status' => 0,]);
            }

            echo "短信通知";
            //短信通知
            $room = Db::name('room')->where(['room_id' => $order_room['room_id']])->find();
            $mobiles = StoreAdmin::manageMobileList($room['join_id'], $order_room['store_id']);
            if ($mobiles) {

                Manage::orderEnd($mobiles, [
                    $room['room_name']
                ]);
            }
            echo "电源操作";
            if ($device && $room_info['electric_stop_status']) {
                if ($device['voice']) {
                    $voice = explode("|", $device['voice']);
                    if (isset($voice[1])) {
                        $voice = $voice[1];
                    } else {
                        $voice = "电源已关闭";
                    }
                } else {
                    $voice = "电源已关闭";
                }
                echo "电源操作开始";

                $res =HardwareClout::AirSwitch()->ElectricityStop($device['device_sn']);

                if ($res["err"]) {

                    echo "关电失败";
                    Db::name('device_record')->insert([
                        'username' => '系统定时关电',
                        'title' => '关电失败',
                        'created_at' => time(),
                        'device_name' => $device['device_name'],
                        'device_sn' => $device['device_sn'],
                        'join_id' => $device['join_id'],
                        'room_name' => $room_info['room_name'],
                    ]);
                    $redis = Redis::redis();
                    $key =$device['device_sn'].":stopPowerSupply";
                    if ($redis->get($key) <3) {
                        $redis->incr($key);
                        $redis->expire($key,60);

                        return true;
                    }
                } else {

                    echo "关电成功";
                    Db::name('device_record')->insert([
                        'username' => '系统定时关电',
                        'title' => '关电成功',
                        'created_at' => time(),
                        'device_name' => $device['device_name'],
                        'device_sn' => $device['device_sn'],
                        'join_id' => $device['join_id'],
                        'room_name' => $room_info['room_name'],
                    ]);
                    HardwareClout::AirSwitch()->ElectricityStop($device['device_sn']);

//                    throw new \Exception('关电失败');
                }
            }


        } catch (\Exception $e) {


            throw new \Exception($e->getMessage());


        }
    }
}

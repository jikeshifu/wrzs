<?php
declare (strict_types=1);

namespace app\command;

use app\common\device\air_switch\AirSwitchFactory;
use app\common\kg\Kg;
use app\module\hardwareCloud\HardwareClout;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class RoomStart extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('roomStart')
            ->setDescription('the roomStart command');
    }

    protected function execute(Input $input, Output $output)
    {
        $Beanstalkd = Kg::app()->Beanstalkd();

        while (true) {
            //读取管道队列
            $job = $Beanstalkd->watch('rrt-roomStart')->reserve();

            $order_id = $job->getData();

            try {
                $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();
                if ($order_room) {
                    if ($order_room['room_status'] == 4) {
                        echo "订单已经取消" . $order_id;
                    } else {
                        $this->room($order_id, $order_room);
                    }
                }
                $Beanstalkd->delete($job);


            } catch (\Exception $e) {

                print_r($e->getMessage());

            }
        }
        // 指令输出
        $output->writeln('roomStart');
    }

    public function room($order_id, $order_room)
    {


        try {

            //修改房间状态为开始
            Db::name('order_room')->where(['order_id' => $order_id])->update([
                'room_status' => 2,
                'electric_number' => $order_room['electric_number'] + 1
            ]);
            Db::name('order')->where(['order_id' => $order_id])->update(['order_status' => 2]);
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
            //electric_status
            if ($device && $room_info['electric_status']) {
                if($device['voice']){
                    $voice = explode("|", $device['voice']);
                    $voice =$voice[0];
                }else{
                    $voice ="电源已打开";
                }




                $res =HardwareClout::AirSwitch()->ElectricityStart($device['device_sn']);
                if ($res["err"]) {
                    Db::name('device_record')->insert([
                        'username' => '系统定时开电',
                        'title' => '开电失败',
                        'created_at' => time(),
                        'device_name' => $device['device_name'],
                        'device_sn' => $device['device_sn'],
                        'join_id' => $device['join_id'],
                        'room_name' => $room_info['room_name'],
                    ]);
                    if ($order_room['electric_number'] < 5) {
                        throw new \Exception('开电失败' . json_encode($res));
                    }
                } else {
                    Db::name('device_record')->insert([
                        'username' => '系统定时开电',
                        'title' => '开电成功',
                        'created_at' => time(),
                        'device_name' => $device['device_name'],
                        'join_id' => $device['join_id'],
                        'device_sn' => $device['device_sn'],
                        'room_name' => $room_info['room_name'],
                    ]);


                }
            }


        } catch (\Exception $e) {


            throw new \Exception($e->getMessage());


        }
    }
}

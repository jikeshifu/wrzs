<?php


namespace app\admin_h5_api\controller\store;


use app\common\device\lock\LockFactory;
use app\common\kg\Kg;
use app\module\hardwareCloud\HardwareClout;
use app\server\device\DeviceRecord;
use think\facade\Db;

class Room
{

    public function editStatus()
    {
        $status = input('post.status');
        $room_id = input('post.room_id');
        if ($status != 1) {
            $status = 0;
        }
        $err = \app\server_api\model\store\Room::editStatusVerification(['room_id' => $room_id]);
        if (!$err) {
            return json([
                'status' => 0,
                'msg' => \app\server_api\model\store\Room::$error_msg,
            ]);
        }
        \app\server_api\model\store\Room::where(['room_id' => $room_id])->save(['status' => $status]);

        return json([
            'status' => 1,
            'msg' => '操作成功',
        ]);
    }

    public function openDevice()
    {
        $room_id = input('post.room_id');
        $type = input('post.type');
        if ($type == 1) {
            $device_type = 1;
        } else if ($type == 2) {
            $device_type = 2;
        } else {
            $device_type = 3;
        }

        $device = Db::name('device')->where([
            'room_id' => $room_id,
            'device_type' => $device_type,
            'deleted_at' => 0
        ])->find();
        if (!$device) {
            return json([
                'status' => 0,
                'msg' => '未绑定设备',
            ]);
        }
        $voice = explode("|", $device['voice']);
        switch ($type) {
            case 2:
            case 1;

            $res  = HardwareClout::WifiLock()->OpenLock($device['device_sn']);

                DeviceRecord::add($device,$room_id,'管理端控制','控制门锁');
                break;
                break;
            case 3;
                DeviceRecord::add($device,$room_id,'管理端控制','启动电源');


                $res  = HardwareClout::AirSwitch()->ElectricityStart($device['device_sn']);

                break;
            case 4;

                DeviceRecord::add($device,$room_id,'管理端控制','关闭电源');
                $res = HardwareClout::AirSwitch()->ElectricityStop($device['device_sn']);
                break;
            default;
                $res = "操作错误";
        }
        if ($res["err"]) {
            return json([
                'status' => 0,
                'msg' => $res["err"],
                'sb'=>$device
            ]);
        }
        return json([
            'status' => 1,
            'msg' => '操作成功',
        ]);
    }
}

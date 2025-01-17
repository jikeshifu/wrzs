<?php


namespace app\server\device;


use think\facade\Db;

class DeviceRecord
{
    static function add($device,$room_id,$name,$type){

        $room =Db::name("room")->where(["room_id"=>$room_id])->find();
        Db::name('device_record')->insert([
            'username' => $name,
            'title' => $type,
            'created_at' => time(),
            'device_name' => $device['device_name'],
            'device_sn' => $device['device_sn'],
            'join_id' => $device['join_id'],
            'room_name' => $room['room_name'],
        ]);
    }
}

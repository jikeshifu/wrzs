<?php


namespace app\server\room;


use think\facade\Db;

class RoomExtend
{
    static function add($data = [])
    {
        if ($data["room_id"]) {
            $data["start_time_slot"] = input("start_time_slot","00:00");
            $data["end_time_slot"] = input("end_time_slot","23:30");

           Db::name("room_extend")->insert($data);
        }
    }
    static function edit($data = [])
    {
        if ($data["room_id"]) {
            if(input("start_time_slot")){
                $data["start_time_slot"] = input("start_time_slot");
                $data["end_time_slot"] = input("end_time_slot");
                $data["updated_at"] = time();
                Db::name("room_extend")->where(['room_id'=>$data["room_id"]])->update($data);
            }

        }
    }
}

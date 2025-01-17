<?php


namespace app\server_api\model\store;

use think\Model;
class Room extends Model
{
    static $error_msg="";
    static function editStatusVerification($data)
    {
        if (!$data['room_id']) {
            self::$error_msg = "room_id不能为空";
            return false;
        }
        return true;
    }


    public function store(){
        return $this->hasOne(Store::class,'store_id','store_id');
    }

    public function roomExtend(){
        return $this->hasOne(RoomExtend::class,'room_id','room_id')->bind(["start_time_slot","end_time_slot"]);
    }
}

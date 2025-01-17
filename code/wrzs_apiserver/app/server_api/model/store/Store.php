<?php


namespace app\server_api\model\store;

use think\Model;
class Store extends Model
{
    static $error_msg="";
    public function roommin(){
        return $this->hasOne(Room::class,'store_id','store_id')->where([
            'deleted_at'=>0,
            'status'=>1
        ])->order('room_price asc')->bind(['room_price']);
    }

    public function roomList(){
        return $this->hasMany(Room::class,'store_id','store_id')->where([
            'deleted_at'=>0,
            'status'=>1
        ])->order('sort desc');
    }
    public function roomListH5(){
        return $this->hasMany(Room::class,'store_id','store_id')->where([
            'deleted_at'=>0,

        ])->order('sort desc');
    }
    static function editStatusVerification($data)
    {
        if (!$data['store_id']) {
            self::$error_msg = "store_id不能为空";
            return false;
        }
        return true;
    }
}

<?php


namespace app\server_api\model\device;


use app\server_api\model\store\Room;
use app\server_api\model\store\Store;
use think\Model;

class Device extends Model
{
    public function room(){
        return $this->hasOne(Room::class,'room_id','room_id');
    }

    public function store(){
        return $this->hasOne(Store::class,'store_id','store_id');
    }
}

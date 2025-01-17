<?php


namespace app\server_api\model\cabinet;



use app\server_api\model\store\Room;
use app\server_api\model\store\Store;
use think\Model;
class Cabinet extends Model
{
    public function room(){
        return $this->hasOne(Room::class,'room_id','room_id')->bind(['room_name']);
    }
    public function store(){
        return $this->hasOne(Store::class,'store_id','store_id')->bind(['store_name']);
    }
}

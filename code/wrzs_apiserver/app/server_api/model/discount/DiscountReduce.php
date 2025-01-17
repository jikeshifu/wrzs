<?php


namespace app\server_api\model\discount;


use app\server_api\model\store\Room;
use think\Model;

class DiscountReduce extends Model
{
    public function room(){
        return $this->hasOne(Room::class,'room_id','room_id')->bind(['room_name']);
    }
}

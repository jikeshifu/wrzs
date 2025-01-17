<?php


namespace app\api\model\room;

use app\api\model\order\Order;
use app\api\model\order\OrderRoom;
use app\api\model\order\OrderRoomRenew;
use app\server_api\model\cabinet\Cabinet;
use app\server_api\model\store\Store;
use think\Model;

class RoomKey extends Model
{
    public function orderRoom()
    {
        return $this->hasOne(OrderRoom::class, 'order_id', 'order_id');
    }
    public function cabinet()
    {
        return $this->hasOne(Cabinet::class, 'room_id', 'room_id');
    }
    public function order(){
        return $this->hasOne(Order::class, 'order_id', 'order_id')->bind(['order_price','deposit','order_profit']);
    }
    public function store(){
        return $this->hasOne(Store::class, 'store_id', 'store_id')->bind(['address','longitude','latitude','store_name','store_image','label',"contact"]);
    }
    public function orderRoomRenewList()
    {
        return $this->hasMany(OrderRoomRenew::class, 'pid', 'order_id')->where(['order_status'=>3]);
    }
}

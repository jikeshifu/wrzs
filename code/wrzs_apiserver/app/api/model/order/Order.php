<?php


namespace app\api\model\order;

use app\server_api\model\discount\DiscountReduce;
use app\server_api\model\store\Store;
use think\Model;

class Order extends Model
{
    public function orderRoom()
    {
        return $this->hasOne(OrderRoom::class, 'order_id', 'order_id');
    }
    public function orderRoomBind()
    {
        return $this->hasOne(OrderRoom::class, 'order_id', 'order_id')->bind([
            'start_time',
            'end_time',
            'room_info'
        ]);
    }
    public function orderRoomRenew()
    {
        return $this->hasOne(OrderRoomRenew::class, 'order_id', 'order_id')->bind(['number']);
    }
    public function store(){
        return $this->hasOne(Store::class, 'store_id', 'store_id')->bind(['store_name']);
        }
    public function orderRoomRenewList()
    {
        return $this->hasMany(OrderRoomRenew::class, 'pid', 'order_id')->where(['order_status'=>3]);
    }
    public function orderReduce()
    {
        return $this->hasOne(OrderReduce::class, 'order_id', 'order_id');
    }
}

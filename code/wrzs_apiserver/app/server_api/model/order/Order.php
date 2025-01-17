<?php


namespace app\server_api\model\order;

use app\model\order\OrderCabinet;
use app\server_api\model\member\MemberWechat;
use app\server_api\model\store\Store;
use think\Model;

class Order extends Model
{

    public function orderCabinet(){
        return $this->hasOne(OrderCabinet::class,'order_id','order_id');
    }
    public function orderRoom(){
        return $this->hasOne(OrderRoom::class,'order_id','order_id');
    }
    public function member(){
        return $this->hasOne(MemberWechat::class,'member_id','member_id');
    }
    public function store(){
        return $this->hasOne(Store::class,'store_id','store_id');
    }
}

<?php

namespace app\model\order;


use app\api\model\order\OrderRoom;
use app\common\code\ErrorCode;
use app\common\kg\Kg;
use app\common\user\User;
use app\model\shop\Goods;
use app\server_api\model\member\MemberWechat;
use think\Model;

class Order extends Model
{

    public $field = [
        'order_sn',
        'order_type',

    ];
    public $error =null;
    public function orderRoom()
    {
        return $this->hasOne(OrderRoom::class, 'order_id', 'order_id');
    }
    public function memberWechat()
    {
        return $this->hasOne(MemberWechat::class, 'member_id', 'member_id');
    }
    public function orderCabinet()
    {
        return $this->hasOne(OrderCabinet::class, 'order_id', 'order_id');
    }
    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class, 'order_id', 'order_id');
    }
    public function orderAddress()
    {
        return $this->hasOne(OrderAddress::class, 'order_id', 'order_id');
    }
    public function add($data)
    {
        $data['member_id']=User::uid();
        $data['order_sn']=Kg::app()->order()->orderSn();
        $data['created_at']=time();
        $data['order_profit']= $data['order_price'];

        return   $this->insertGetId($data);
    }
    public function edit($data)
    {

        if(!array_key_exists('goods_id',$data) || !$data['goods_id'] ){
            $this->error= ErrorCode::$adminCode[6];
            return ;
        }
        $data['updated_at']=time();
        $this->where(['goods_id'=>$data['goods_id']])->save($data);
        return ;
    }
    public function del($data)
    {

        if(!array_key_exists('goods_id',$data) || !$data['goods_id'] ){
            $this->error= ErrorCode::$adminCode[6];
            return ;
        }
        $data['deleted_at']=time();
        $this->where(['goods_id'=>$data['goods_id']])->save($data);
        return ;
    }
}

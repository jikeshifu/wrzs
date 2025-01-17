<?php

namespace app\model\join;


use app\common\code\ErrorCode;
use app\common\kg\Kg;
use app\common\user\User;
use app\model\shop\Goods;
use think\Model;

class JoinApply extends Model
{

    public $field = [
        'name',
        'mobile',
        'city',
        'remarks'

    ];
    public $error =null;

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

        $data['created_at']=time();
        if(!$data['mobile']){
            $this->error= ErrorCode::$code[13];
            return ;
        }

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

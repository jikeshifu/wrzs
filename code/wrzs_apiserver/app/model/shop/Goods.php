<?php

namespace app\model\shop;


use app\common\code\ErrorCode;
use think\Model;

class Goods extends Model
{

    public $field = [
        'goods_name',
        'goods_price',
        'goods_stock',
        'goods_sold',
        'goods_about',
        'goods_id',
        'goods_image',
        'sort',
        'status',
        'type_id',
        'shipping_remarks',
        'recommend_status'
    ];
    public $error =null;

    public function goodsType()
    {
        return $this->hasOne(GoodsType::class, 'type_id', 'type_id')->bind(['type_name']);
    }
    public function add($data)
    {
        if(!$data['goods_name']){
            $this->error= ErrorCode::$adminCode[7];
            return ;
        }
        $data['created_at']=time();
        $this->insert($data);
        return ;
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

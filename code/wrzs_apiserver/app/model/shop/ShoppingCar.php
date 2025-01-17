<?php

namespace app\model\shop;

use app\common\code\ErrorCode;
use app\common\user\User;
use think\Model;

class ShoppingCar extends Model
{

    public $field = [

        'goods_id',
        'number',
        'id'

    ];
    public $error =null;

    public function goodsInfo()
    {
        return $this->hasOne(Goods::class, 'goods_id', 'goods_id');
    }
    public function add($data)
    {
        if(!array_key_exists('goods_id',$data) || !$data['goods_id'] ){
            $this->error= ErrorCode::$adminCode[8];
            return ;
        }
        $data['member_id']=User::uid();
        $where =['member_id'=> $data['member_id'],'goods_id'=>$data['goods_id'],'deleted_at'=>0];
       $res = $this->where($where)->find();
       if($res){
           $data['number']=$res['number']+$data['number'];
           $data['updated_at']=time();
           $this->where($where)->save($data);
       }else{
           $data['created_at']=time();
           $this->insert($data);
       }

        return ;
    }
    public function edit($data)
    {

        if(!array_key_exists('id',$data) || !$data['id'] ){
            $this->error= ErrorCode::$adminCode[6];
            return ;
        }
        $data['updated_at']=time();
        $this->where(['id'=>$data['id']])->save($data);
        return ;
    }
    public function del($data)
    {

        if(!array_key_exists('id',$data) || !$data['id'] ){
            $this->error= ErrorCode::$adminCode[6];
            return ;
        }
        $data['deleted_at']=time();
        $this->where(['id'=>$data['id']])->delete();
        return ;
    }
    public function dels($data)
    {

        $where['member_id']=User::uid();
        $data['deleted_at']=time();
        $this->where($where)->delete();
        return ;
    }
}

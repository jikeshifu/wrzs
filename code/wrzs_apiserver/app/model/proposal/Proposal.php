<?php

namespace app\model\proposal;


use app\common\code\ErrorCode;
use app\common\kg\Kg;
use app\common\user\User;
use app\model\shop\Goods;
use app\server_api\model\member\MemberWechat;
use think\Model;

class Proposal extends Model
{

    public $field = [

        'content',


    ];
    public $error =null;


    public function member()
    {
        return $this->hasOne(MemberWechat::class, 'member_id', 'member_id');
    }
    public function add($data)
    {
        $data['member_id']=User::uid();

        $data['created_at']=time();
        if(!$data['content']){
            $this->error= ErrorCode::$code[14];
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

<?php

namespace app\model\shop;


use app\common\code\ErrorCode;
use think\Model;

class GoodsType extends Model
{

    public $field = [
        'type_name',
        'type_id',
        'status',
        'sort'
    ];
    public $error =null;


    public function add($data)
    {
        if(!$data['type_name']){
            $this->error= ErrorCode::$adminCode[9];
            return ;
        }
        $data['created_at']=time();
        $this->insert($data);
        return ;
    }
    public function edit($data)
    {

        if(!array_key_exists('type_id',$data) || !$data['type_id'] ){
            $this->error= ErrorCode::$adminCode[6];
            return ;
        }
        $data['updated_at']=time();
        $this->where(['type_id'=>$data['type_id']])->save($data);
        return ;
    }
    public function del($data)
    {

        if(!array_key_exists('type_id',$data) || !$data['type_id'] ){
            $this->error= ErrorCode::$adminCode[6];
            return ;
        }
        $data['deleted_at']=time();
        $this->where(['type_id'=>$data['type_id']])->save($data);
        return ;
    }
}

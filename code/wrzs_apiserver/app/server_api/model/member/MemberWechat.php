<?php


namespace app\server_api\model\member;


use think\Model;

class MemberWechat extends Model
{
    public function memberWallet(){

            return $this->hasOne(MemberWallet::class,'member_id','member_id');

    }
}

<?php


namespace app\model\join;


use think\Model;

class JoinWithdrawal extends Model
{
    public function joinUser()
    {
        return $this->hasOne(JoinUser::class, 'user_id', 'join_id');
    }
}

<?php


namespace app\api\controller\member;


use app\common\code\SuccessCode;
use app\common\user\User;

class MemberWallet
{
    public function list()
    {
//        $member_id = User::uid();
//        $MemberWallet = \app\model\member\MemberWallet::where(['member_id' => $member_id])->with(['store'])->select()->toArray();
        return json(SuccessCode::statusOkf(['list'=>[]]));
    }
}

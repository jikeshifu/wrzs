<?php


namespace app\api\controller\user;


use app\common\code\Code;
use app\common\code\SuccessCode;
use app\common\user\User;
use app\module\member\server\MemberWechatServer;
use think\facade\Db;

class Balance
{
    public function info()
    {

        $member_id = User::uid();
        $member_wallet=Db::name('member_wallet')->where([
            'member_id' => $member_id,
        ])->find();
        if(!$member_wallet){
            Db::name('member_wallet')->insert([
                'member_id' => $member_id,
            ]);
            $member_wallet=Db::name('member_wallet')->where([
                'member_id' => $member_id,
            ])->find();
        }

        $member_wallet['coupon_number'] = Db::name('member_coupons')->where([
            'member_id' => $member_id,
            'use_time' => 0,

        ])->where('end_time','>',time())->count();
        $member_wallet['is_admin'] = 1;

        return json(  Code::Ok([
            "info"=>$member_wallet
        ]));
    }
}

<?php


namespace app\common\member;


use think\facade\Db;

class MemberWallet
{

    /**
     * 增加余额
     * @param int $member_id
     * @param float $money
     * @param string $title 为什么增加
     */
    static function increase(int $member_id,float $money,int $order_id,string $title){


        //修改用户余额
        $member_wallet = Db::name("member_wallet")->where(['member_id' => $member_id])->find();
        $moneys = $member_wallet['money'] + $money;
        Db::name("member_wallet")->where(['member_id' =>$member_id])->update([
            'money' => $moneys
        ]);
        Db::name('member_pay_record')->insert([
            'title' => $title,
            'created_at' => time(),
            'price' =>$money,
            'member_id' => $member_id,
            'order_id' => $order_id,
            'type'=>1
        ]);

    }
}

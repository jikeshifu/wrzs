<?php


namespace app\api\controller\pay\notify;


use app\model\member\MemberWallet;
use think\facade\Db;

class RechargePackage
{
    static function index($order)
    {
        //修改订单状态为完成
        Db::name('order')->where(['order_id' => $order['order_id']])->update([
            'order_status' => 3,
            'pay_type' => 'wechat',
            "finish_time"=>time()

        ]);
        //查询充值多少
        $order_recharge_package = Db::name('order_recharge_package')->where([
            'order_id' => $order['order_id'],
        ])->find();
        //修改用户余额
        MemberWallet::increase($order['member_id'], $order_recharge_package['profit'], $order['order_id'],  '用户购买套餐');
        //修改用户余额

    }
}

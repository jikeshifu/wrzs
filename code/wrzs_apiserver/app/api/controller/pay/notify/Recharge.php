<?php


namespace app\api\controller\pay\notify;


use app\common\member\MemberWallet;
use think\facade\Db;

class Recharge
{
    static function index($order)
    {
        //修改订单状态为完成
        Db::name('order')->where(['order_id' => $order['order_id']])->update(['order_status' => 3]);
        //修改用户余额
        MemberWallet::increase($order['member_id'],$order['order_price'],'用户充值',$order['order_id']);


    }
}

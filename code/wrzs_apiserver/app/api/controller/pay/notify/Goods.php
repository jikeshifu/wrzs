<?php


namespace app\api\controller\pay\notify;


use think\facade\Db;

class Goods
{
    static function index($order,$pay_type){

        //修改优惠券为使用
        $order_coupons = Db::name('order_coupons')->where([
            'order_id' => $order['order_id']
        ])->find();
        if($order_coupons){
            Db::name('member_coupons')->where([
                'member_coupons_id' => $order_coupons['member_coupons_id']
            ])->update(['use_time'=>time()]);
        }
        Db::name('order')->where(['order_id' => $order['order_id']])->update([
            'pay_status' => 1,
            'pay_time' => time(),
            'pay_type' => $pay_type,
            'order_status' => 1,
        ]);
    }
}

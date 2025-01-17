<?php


namespace app\api\controller\order;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use think\facade\Db;

class Coupons
{
    public function placeOrder()
    {

        $member_coupons_id = input('post.member_coupons_id');
        $order_id = input('post.order_id');
        $member_coupons = Db::name('member_coupons')->where([
            'member_coupons_id' => $member_coupons_id,
            'use_time' => 0
        ])->find();
        if (!$member_coupons) {
            return json(ErrorCode::$code[1]);
        }
        //查询订单信息
        //订单

        $order_coupons = Db::name('order_coupons')->where(['order_id' => $order_id])->find();
        if (!$order_coupons) {
            $order = Db::name('order')->where(['order_id' => $order_id])->find();
            $room_price = $order['order_price'] - $member_coupons['money'];
            if ($room_price < 0) {
                $room_price = 0;
            }
            Db::name('order')->where(['order_id' => $order_id])->update([
                'order_price' => $room_price,
                'order_profit' => $room_price,
            ]);
            $member_coupons['order_id'] = $order_id;
//        unset($member_coupons['wxapp_id']);
            unset($member_coupons['use_time']);
            unset($member_coupons['updated_at']);
            Db::name('order_coupons')->insert($member_coupons);

        }

        $res = SuccessCode::$code[0];
        return json($res);
    }
}

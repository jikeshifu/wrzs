<?php


namespace app\common\order\cancel;


use app\common\cache\Redis;
use app\common\kg\Kg;

use app\model\member\MemberWallet;
use think\facade\Db;

class Refund
{
    static function order($order, $title = '取消订单')
    {

        if ($order['pay_type'] == 'wechat') {

            $app = Kg::app()->watch()->payment();
            // 参数分别为：商户订单号、商户退款单号、订单金额、退款金额、其他参数

            $refund = $app->refund->byOutTradeNumber($order['order_sn'], $order['order_sn'], $order['order_price'] * 100, $order['order_price'] * 100, [
                // 可在此处传入其他参数，详细参数见微信支付文档
                'refund_desc' => '用户取消',
            ]);

            if ($refund['return_code'] != 'SUCCESS' || $refund['result_code'] != 'SUCCESS') {

                throw new \Exception(json_encode($refund));
            }

        } else if ($order['pay_type'] == 'balance') {
            MemberWallet::increase($order['member_id'], $order['order_price'], $order['order_id'], $order['store_id'], $title);


        } else {
            throw new \Exception('错误未知支付');
        }
    }

    static function orderCancel($order, $title = '取消订单')
    {


        if ($order['order_status'] == 4) {
      return ["msg"=>"订单已经取消"];
        }
        if ($order['pay_type'] == 'wechat') {

            $app = Kg::app()->watch()->payment();
            // 参数分别为：商户订单号、商户退款单号、订单金额、退款金额、其他参数

            $refund = $app->refund->byOutTradeNumber($order['order_sn'], $order['order_sn'], $order['order_price'] * 100, $order['order_price'] * 100, [
                // 可在此处传入其他参数，详细参数见微信支付文档
                'refund_desc' => '用户取消',
            ]);

            if ($refund['return_code'] != 'SUCCESS' || $refund['result_code'] != 'SUCCESS') {



                return ["msg"=>$refund["err_code_des"]];
            }

        } else if ($order['pay_type'] == 'balance') {
            MemberWallet::increase($order['member_id'], $order['order_price'], $order['order_id'], $order['store_id'], $title);

        }


        Db::name("order")->where(["order_id" => $order['order_id']])->update(["order_status" => 4]);

        if ($order["order_type"] == "adminRecharge" || $order["order_type"] == "recharge") {
            if ($order["order_type"] == "rechargePackage") {
                $order_recharge_package = Db::name("order_recharge_package")->where(["order_id" => $order['order_id']])->find();
                $order['order_price'] = $order_recharge_package["profit"];
            }
            MemberWallet::reduce($order["member_id"], $order['order_price'], $order['order_id'], "取消订单");
        }else if($order["order_type"]=="room"){
            //修改订单状态为取消
            Db::name('order')->where(['order_id' => $order['order_id']])->update(['order_status' => 4]);
            //修改订单房间状态为取消
            Db::name('order_room')->where(['order_id' => $order['order_id']])->update(['room_status' => 4]);
            //删除已定时间段
            $redis = Redis::redis();
            $redis->hDel('room_id:' . $order['room_id'], $order['order_id']);
            //删除订单钥匙
            Db::name('room_key')->where(['order_id'=> $order['order_id']])->update(['deleted_at'=>time()]);
            //修改房间状态
            Db::name('room')->where(['room_id' => $order['room_id']])->update(['room_status' => 1]);
        }



    }
}

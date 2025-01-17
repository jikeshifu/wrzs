<?php


namespace app\api\controller\pay;

use app\common\cache\Redis;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\common\user\User;
use EasyWeChat\Factory;
use think\facade\Db;

class Wechat
{

    public function index()
    {


        $order_id = input('post.order_id');
        $order = Db::name('order')->where(['order_id' => $order_id])->find();
        if ($order['pay_status'] == 1) {
            return json(['status' => 0, 'msg' => '订单已支付']);
        }
        if ($order['created_at'] < (time() - 300)) {
            return json(['status' => 0, 'msg' => '订单超时请重新下单']);
        }
        if ($order['room_id']) {
            $redis = Redis::redis();
            $key = "payLock:" . $order['room_id'];
            if ($redis->get($key) != $order_id) {

                if (!$redis->set($key, $order_id, ['nx', 'ex' => 20])) {
                    return json(['status' => 0, 'msg' => '当前房间有人正在支付请稍等10秒']);
                }
            }

        }
        if ($order['order_type'] == 'room') {
            $body = '房间下单';
            //判断当前时间是否可选
            $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();
            $room = json_decode($order_room['room_info'], true);
            if ($room['reserve_status'] == 0) {
                Kg::app()->room()->timeSlot($order_room['room_id'], $order_room['start_time'], $order_room['end_time']);
            }

        } else if ($order['order_type'] == 'roomRenew') {
            $body = '用户续费房间';
        } elseif ($order['order_type'] == 'roomRenew') {
            $body = '用户充值';
        } else {
            $body = $order['order_type'];
        }

        $app = Kg::app()->watch()->payment();
        if ($order['order_price'] < 0.01) {
            $order['order_price'] = 0.01;
        }
        $result = $app->order->unify([
            'body' => $body,
            'out_trade_no' => $order['order_sn'],

            'total_fee' => $order['order_price'] * 100,
//            'spbill_create_ip' => '111.230.97.87', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址

            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => User::userInfo()['openid'],

        ]);

        if ($result['return_code'] != "SUCCESS") {
            $err = ErrorCode::$code[0];
            $err['msg'] = $result['return_msg'];
            return json($err);
        }
        try {
            $config = $app->jssdk->bridgeConfig($result['prepay_id'], false); // 返回数组
            $res = SuccessCode::$code[0];
            $res['pay'] = $config;
        } catch (\Exception $e) {

            print_r($result);
            throw new \Exception($e->getMessage());

        }


        return json($res);

    }
}

<?php


namespace app\api\controller\order;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\common\user\User;
use app\model\order\Order;
use think\facade\Db;

class Cabinet
{

    public function oderInfo()
    {
        $order_id = input('order_id');
        $order = Order::where(['order_id' => $order_id])->with(['orderCabinet'])->find()->toArray();
        $res = SuccessCode::$statusOk;
        $order['orderCabinet']['goods_info'] = json_decode($order['orderCabinet']['goods_info'], true);
        $member_wallet = Db::name("member_wallet")->where(['member_id' => User::uid(),'store_id'=>$order['store_id']])->find();
        $res['order_info'] = $order;

        $res['money'] = $member_wallet['money'];
        return json($res);
    }

    public function openLock()
    {
        $order_id = input('order_id');
        $order = Order::where(['order_id' => $order_id])->with(['orderCabinet'])->find()->toArray();




        //订单未支付
        if ($order['order_status'] != 3) {
            return json(ErrorCode::$code[10]);
        }
        //订单超时
        if ($order['pay_time'] < (time() - 300)) {
            return json(ErrorCode::$code[11]);
        }
        $res = Kg::app()->device()->cabinet($order['orderCabinet']['device_sn'])->open($order['orderCabinet']['cabinet_number']);
        if (!$res) {
            Db::name('order_cabinet')->where(['order_id' => $order_id])->update([
                "lock_status" => 1
            ]);
            return json(SuccessCode::$statusOk);
        }else{
            return json(ErrorCode::$code[12]);
        }

    }
}

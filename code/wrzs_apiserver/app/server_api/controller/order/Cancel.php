<?php


namespace app\server_api\controller\order;


use app\common\Common;
use app\common\kg\Kg;
use app\common\member\MemberWallet;
use app\common\user\User;
use app\server_api\model\order\Order;
use think\facade\Db;
use think\facade\Request;


class Cancel
{



    /**
     * 取消订单
     */
    public function index(){
        $order_id = input('post.order_id');

        $order = Db::name('order')->where(['order_id' => $order_id])->find();

        if ($order['order_status'] == 4) {
            return json(['status' => 0, 'msg' => '订单已取消']);
        }
        if ($order['pay_status'] != 1) {
            return json(['status' => 0, 'msg' => '订单未支付']);
        }
        \app\common\order\cancel\Cancel::order($order_id);
        return json(['status' => 1, 'msg' => '取消成功']);
    }
}

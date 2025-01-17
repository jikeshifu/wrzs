<?php


namespace app\api\controller\order;


use app\common\user\User;
use think\facade\Db;

class Cancel
{

    public function index()
    {
        $order_id = input('post.order_id');
        $member_id = User::uid();
        $order = Db::name('order')->where(['order_id' => $order_id, 'member_id' => $member_id])->find();

        if ($order['order_status'] == 4) {
            return json(['status' => 0, 'msg' => '订单已取消']);
        }
        if ($order['pay_status'] != 1) {
            return json(['status' => 0, 'msg' => '订单未支付']);
        }

        if($order['order_type']=="room" || $order['order_type']=="roomOne"){
            //查询房间判断是否可以自主取消
           $room= Db::name("room")->where(['room_id'=>$order['room_id']])->field('cancel_status')->find();
           if(!$room['cancel_status']){
               return json(['status' => 0, 'msg' => '该房间不能自主取消，请联系管理员']);
           }

        }
        \app\common\order\cancel\Cancel::order($order_id);
        return json(['status' => 1, 'msg' => '取消成功']);
    }


}

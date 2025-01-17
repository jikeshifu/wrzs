<?php


namespace app\api\controller\order;


use app\api\model\order\Order;

use app\common\kg\Kg;

use app\common\user\User;
use think\facade\Db;

class RoomOne
{
    public function placeOrder()
    {

        //1、查询房间信息
        $room_id = input('post.room_id');
        $wxapp_id = input('post.wxapp_id');
        $room = Db::name('room')->where(['room_id' => $room_id])->find();


        $member_id = User::uid();
        $order['order_sn'] = Kg::app()->order()->orderSn();
        $order['order_type'] = 'roomOne';
        $order['wxapp_id'] = $wxapp_id;

        $order['order_total'] =      $room['room_price'];
        $member_coupons_id = input('post.coupons_id');
        //如果有选择优惠券
        $member_coupons = null;
        if ($member_coupons_id) {
            //查询优惠券信息
            $member_coupons = Db::name('member_coupons')->where([
                'member_id' => $member_id,
                'use_time' => 0
            ])->find();
            if($member_coupons){
                $room['room_price'] =$room['room_price']-   $member_coupons['money'];
                if($room['room_price']<0){
                    $room['room_price']=0;
                }
            }

        }
        $order['order_price'] = $room['room_price'];
        $order['order_profit'] = $room['room_price'];
        $created_at = time();
        $order['member_id'] = $member_id;
        $order['room_id'] = $room['room_id'];
        $order['store_id'] = $room['store_id'];
        $order['created_at'] = $created_at;
        $order_room['member_id'] = $member_id;
        $order_room['start_time'] = $created_at;
        $order_room['end_time'] = $created_at;
        $order_room['room_id'] = $room['room_id'];
        $order_room['store_id'] = $room['store_id'];
        $order_room['room_info'] = json_encode($room);
        $order_room['created_at'] = $created_at;

// 启动事务  fj202108021650389223510516522867
        $ReduceRes = Reduce::reduce($room['room_id'], $order['order_price']);
        $order['order_price'] =  $ReduceRes['price'];
        Db::startTrans();
        try {
            $order_id = Db::name('order')->insertGetId($order);
            $order_room['order_id'] = $order_id;
            Db::name('order_room')->insert($order_room);

            //如果有使用优惠券
            if ($member_coupons) {
                $member_coupons['order_id']=$order_id;
                unset($member_coupons['wxapp_id']);
                unset($member_coupons['use_time']);
                unset($member_coupons['updated_at']);
                Db::name('order_coupons')->insert($member_coupons);
            }
            //如果满足满减
            if($ReduceRes['reduce']){
                $ReduceRes['reduce']['order_id'] = $order_id;
                Db::name('order_reduce')->insert($ReduceRes['reduce']);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['status' => 0, 'error_code' => 1444, 'msg' => $e->getMessage()]);
        }

        $order_info = Order::where(['order_id' => $order_id])->find()->toArray();


        $member_wallet = Db::name('member_wallet')->where(['member_id' => $member_id])->find();
        $order_info['order_coupons']=$member_coupons;
        $order_info['order_reduce']=$ReduceRes['reduce'];
        return json(['status' => 1, 'msg' => '下单成功', 'order_info' => $order_info, 'money' => $member_wallet['money']]);
    }

}

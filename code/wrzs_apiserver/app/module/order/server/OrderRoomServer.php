<?php


namespace app\module\order\server;


use app\api\controller\order\Reduce;
use app\api\model\order\Order;
use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\common\user\User;
use app\module\member\model\MemberWechat;
use app\module\server\Server;
use app\module\store\server\StoreRoomServer;
use think\facade\Db;

class OrderRoomServer extends Server
{

    static function order($room_id, $start_time, $end_time)
    {
        if (!$start_time || !$end_time || $end_time < $start_time) {
            self::$error = "时间错误";
            return;
        }
        $room = Db::name('room')->where(['room_id' => $room_id])->find();
        unset($room['room_about']);
        if ($room["status"] == 0) {
            return json(['status' => 0, 'error_code' => 1001, 'msg' => '房间暂停服务']);
        }
        if (!$room) {
            return json(['status' => 0, 'error_code' => 1001, 'msg' => '房间不存在']);
        }
        //判断当前房间是否可以重复预定
        if ($room['reserve_status'] == 0) {
            //判断当前时间是否可选
            StoreRoomServer::timeSlot($room_id, $start_time, $end_time);
            if (StoreRoomServer::$error) {
                self::$error = "时间错误";
                return;
            }
        }
        //计算预定了几小时
        $room_time = 3600;
        $time_number = ($end_time - $start_time) / $room_time;
        $member_id = User::uid();
        $order['order_sn'] = Kg::app()->order()->orderSn();
        $order['order_type'] = 'room';
        //计算价格
        $room_price = bcmul($time_number, $room['room_price'], 2);
        $price = $room_price + $room['room_deposit'];
        //获取用户信息
        $MemberWechat = MemberWechat::GetMemberInfoWMemberID($member_id);
        //查询是否管理员

        $store_admin = Db::name("store_admin")->where([
            "join_id" => $room["join_id"],
            "status" => 1,
            "deleted_at" => 0
        ])->select()->toArray();
        foreach ($store_admin as $store_adminVo) {
            if ($store_adminVo["mobile"] == $MemberWechat["mobile"]) {
                $store_id_arr =json_decode( $store_adminVo["store_id_arr"],true);
                foreach ($store_id_arr as $store_id_arrVo) {
                    if ($store_id_arrVo == $room["store_id"]) {
                        $price=0;
                    }
                }
            }

        }



        $order['order_price']=$price;


        $created_at = time();
        $order['member_id'] = $member_id;

        $order['join_id'] = $room['join_id'];
        $order['room_id'] = $room['room_id'];
        $order['store_id'] = $room['store_id'];
        $order['created_at'] = $created_at;
        $order['deposit'] = $room['room_deposit'];
        if ($room['room_deposit'] > 0) {
            $order['deposit_status'] = 1;
        } else {
            $order['deposit_status'] = 3;
        }
        $order_room['join_id'] = $room['join_id'];
        $order_room['member_id'] = $member_id;
        $order_room['start_time'] = $start_time;
        $order_room['end_time'] = $end_time;
        $order_room['room_id'] = $room['room_id'];
        $order_room['store_id'] = $room['store_id'];
        //删除房间介绍
        unset($room['room_about']);
        $order_room['room_info'] = json_encode($room);
        $order_room['created_at'] = $created_at;
// 启动事务  fj202108021650389223510516522867
        $ReduceRes = Reduce::reduce($room['room_id'], $order['order_price']);
        $order['order_price'] = $ReduceRes['price'];


        $order['order_profit'] = $order["order_price"];
        $order['order_total'] = $order["order_price"];
        $order_id = Db::name('order')->insertGetId($order);
        $order_room['order_id'] = $order_id;
        Db::name('order_room')->insert($order_room);
        //如果满足满减
        if ($ReduceRes['reduce']) {
            $ReduceRes['reduce']['order_id'] = $order_id;
            Db::name('order_reduce')->insert($ReduceRes['reduce']);
        }

        $order_info = Order::where(['order_id' => $order_id])->with('orderRoom')->find()->toArray();
        $order_info['orderRoom']['room_info'] = json_decode($order_info['orderRoom']['room_info'], true);
        unset($order_info['orderRoom']['room_info']['room_images']);
        $res = SuccessCode::$code[0];
        $member_wallet = Db::name('member_wallet')->where([
            'member_id' => $member_id,
            'store_id' => $order_info['store_id']])->find();
        if ($member_wallet) {
            $res['money'] = $member_wallet['money'];
        } else {
            $res['money'] = 0;
        }
        $order_info['order_reduce'] = $ReduceRes['reduce'];

        $res['order_info'] = $order_info;

        return $res;

    }
}
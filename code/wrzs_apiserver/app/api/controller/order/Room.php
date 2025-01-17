<?php


namespace app\api\controller\order;


use app\api\model\order\Order;
use app\common\code\SuccessCode;
use app\common\kg\Kg;

use app\common\user\User;
use app\module\member\model\MemberWechat;
use think\facade\Db;

class Room
{
    public function placeOrder()
    {
        $start_time = input('post.start_time');
        $end_time = input('post.end_time');
        if (!$start_time || !$end_time || $end_time < $start_time) {
            return json(['status' => 0, 'error_code' => 1000, 'msg' => '时间错误']);
        }
        $room_id = input('post.room_id');
        $room = Db::name('room')->where(['room_id' => $room_id])->find();
        //判断当前房间是否可以重复预定
        if ($room['reserve_status'] == 0) {
            //判断当前时间是否可选
            Kg::app()->room()->timeSlot($room_id, $start_time, $end_time);
        }
        $room_time = 3600;
        $time_number = ($end_time - $start_time) / $room_time;
        if ($room["status"] == 0) {
            return json(['status' => 0, 'error_code' => 1001, 'msg' => '房间暂停服务']);
        }
        if (!$room) {
            return json(['status' => 0, 'error_code' => 1001, 'msg' => '房间不存在']);
        }
        unset($room['room_about']);
        $member_id = User::uid();
        $order['order_sn'] = Kg::app()->order()->orderSn();
        $order['order_type'] = 'room';
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
        //测试


        $order['order_total'] = $price;

        $order['order_price'] = $price;
        $order['order_profit'] = $price;
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
        Db::startTrans();
        try {
            $order_id = Db::name('order')->insertGetId($order);
            $order_room['order_id'] = $order_id;
            Db::name('order_room')->insert($order_room);
            //如果满足满减
            if ($ReduceRes['reduce']) {
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
        $order_info = Order::where(['order_id' => $order_id])->with('orderRoom')->find()->toArray();
        //进入订单关闭接口


        $order_info['orderRoom']['room_info'] = json_decode($order_info['orderRoom']['room_info'], true);
        unset($order_info['orderRoom']['room_info']['room_images']);
        $res = SuccessCode::$code[0];
        $member_wallet = Db::name('member_wallet')->where([
            'member_id' => $member_id])->find();
        if ($member_wallet) {
            $res['money'] = $member_wallet['money'];
        } else {
            $res['money'] = 0;
        }
        $order_info['order_reduce'] = $ReduceRes['reduce'];

        $res['order_info'] = $order_info;

        return json($res);
    }

    /**
     *
     * 可续费时间段查询
     */
    public function renewTime()
    {
        $order_id = input('post.order_id');
        $room_id = input('post.room_id');
        $room = Db::name('room')->where(['room_id' => $room_id])->find();
        $max = $this->renewTimes($order_id, $room_id);
        if ($room['room_type'] == 2) {

            $max = floor($max);
        }

        if ($max == 0) {

            return json(['status' => 1, 'max' => 0, 'msg' => '该房间不可续费']);
        }

        return json(['status' => 1, 'max' => $max]);


    }

    static function WbRenewTimes($order_id, $room_id)
    {
        $Room = new Room();
        return $Room->renewTimes($order_id, $room_id);
    }

    public function renewTimes($order_id, $room_id)
    {
        //查询当前订单详细信息
        $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();


        //设置最大可定
        $max = 10;

        //查询时间最靠近的订单
        $order_room1 = Db::name('order_room')
            ->where('start_time', '>', $order_room['end_time'])
            ->where([
                'room_id' => $room_id,
            ])->where('room_status', 'in', '1,2')->order('start_time aes')->find();
        if ($order_room1) {
            $sjc = $order_room1['start_time'] - $order_room['end_time'];

            $max = ($sjc / 3600) - 1;

        }


        return $max;
    }

    /**
     * 续费下单
     */

    public function renew()
    {
        $order_id = input('post.order_id');
        $room_id = input('post.room_id');
        $number = input('post.number');

        $max = $this->renewTimes($order_id, $room_id);
        if ($max == 0) {
            return json(['status' => 0, 'msg' => '该房间不可续费']);
        }
        if ($number > $max) {
            return json(['status' => 0, 'msg' => '超出该房间最大可续费时长']);
        }

        $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();
        if ($order_room['room_status'] == 3) {
            return json(['status' => 0, 'msg' => '订单已经结束']);
        }

        //查询房间信息
        $room = Db::name('room')->where(['room_id' => $room_id])->find();
        unset($room['room_about']);
        $member_id = User::uid();
        $order['member_id'] = $member_id;
        $order['order_sn'] = Kg::app()->order()->orderSn();
        $order['order_type'] = 'roomRenew';
        $order['created_at'] = time();
        $order['order_price'] = $room['room_price'] * $number;
        if ($order['order_price'] < 0.01) {
            $order['order_price'] = 0.01;
        }
        $order['order_total'] = $order['order_price'];
        $order['room_id'] = $room['room_id'];
        $order['store_id'] = $room['store_id'];
        $renewOrder_id = Db::name('order')->insertGetId($order);


        if ($room['room_type'] == 1) {
            $room_time = 3600 * $number;
        } elseif ($room['room_type'] == 4) {
            $end_times = date('Y-m-d H:i:s', $order_room['end_time']);
            $room_time = strtotime("$end_times + $number month");
            $room_time = $room_time - $order_room['end_time'];
        } else {
            $room_time = 3600 * 24 * $number;
        }
        //写入续费关联信息
        Db::name('order_room_renew')->insert([
            'order_id' => $renewOrder_id,
            'pid' => $order_id,
            'created_at' => time(),
            'number' => $number,
            'renew_time' => $room_time
        ]);
        $member_wallet = Db::name('member_wallet')->where([
            'member_id' => $member_id,
            'store_id' => $order_room['store_id']
        ])->find();
        $order_info = Order::where(['order_id' => $renewOrder_id])->with(['orderRoomRenew'])->find();
        $room['room_images'] = json_decode($room['room_images'], true);
        $order_info['room_info'] = $room;

        $res = SuccessCode::$code[0];
        $res['order_info'] = $order_info;
        if (isset($member_wallet['money'])) {
            $res['money'] = $member_wallet['money'];
        } else {
            $res['money'] = 0;
        }

        return json($res);
    }


    public function coupons()
    {


    }

    /**
     * 按月下单
     */
    public function month()
    {


        $room_id = input('post.room_id');
        $wxapp_id = input('post.wxapp_id');
        $start_time = time();
        //计算结束时间
        $time_number = input('post.number');
        if (!$time_number) {
            $time_number = 1;
        }

        $end_times = date('Y-m-d H:i:s', $start_time);
        $end_time = strtotime("$end_times + $time_number month");

        $room = Db::name('room')->where(['room_id' => $room_id, 'wxapp_id' => $wxapp_id])->find();
        //判断当前房间是否可以重复预定
        if ($room['reserve_status'] == 0) {
            //判断当前时间是否可选
            Kg::app()->room()->timeSlot($room_id, $start_time, $end_time);
        }


        unset($room['room_about']);
        if (!$room) {
            return json(['status' => 0, 'error_code' => 1001, 'msg' => '房间不存在']);
        }


        $member_id = User::uid();
        $order['order_sn'] = Kg::app()->order()->orderSn();
        $order['order_type'] = 'room';
        $order['wxapp_id'] = $wxapp_id;
        $room_price = bcmul($time_number, $room['room_price'], 2);
        //如果有选择优惠券
        $member_coupons_id = input('post.coupons_id');
        $member_coupons = null;
        $order['order_total'] = $room_price + $room['room_deposit'];

        if ($member_coupons_id) {
            //查询优惠券信息
            $member_coupons = Db::name('member_coupons')->where([
                'member_id' => $member_id,
                'use_time' => 0
            ])->find();
            if ($member_coupons) {
                $room_price = $room_price - $member_coupons['money'];
                if ($room_price < 0) {
                    $room_price = 0;
                }
            }

        }

        $order['order_price'] = $room_price + $room['room_deposit'];
        $order['order_profit'] = $room_price;
        $created_at = time();
        $order['member_id'] = $member_id;
        $order['room_id'] = $room['room_id'];
        $order['store_id'] = $room['store_id'];
        $order['created_at'] = $created_at;
        $order['deposit'] = $room['room_deposit'];
        if ($room['room_deposit'] > 0) {
            $order['deposit_status'] = 1;
        } else {
            $order['deposit_status'] = 0;
        }
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
        Db::startTrans();
        try {
            $order_id = Db::name('order')->insertGetId($order);
            $order_room['order_id'] = $order_id;
            Db::name('order_room')->insert($order_room);
            //如果有使用优惠券
            if ($member_coupons) {
                $member_coupons['order_id'] = $order_id;
                unset($member_coupons['wxapp_id']);
                unset($member_coupons['use_time']);
                unset($member_coupons['updated_at']);
                Db::name('order_coupons')->insert($member_coupons);
            }
            //如果满足满减
            if ($ReduceRes['reduce']) {
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

        $order_info = Order::where(['order_id' => $order_id])->with('orderRoom')->find()->toArray();

        $order_info['orderRoom']['room_info'] = json_decode($order_info['orderRoom']['room_info'], true);
        unset($order_info['orderRoom']['room_info']['room_images']);
        $member_wallet = Db::name('member_wallet')->where(['member_id' => $member_id])->find();
        $order_info['order_coupons'] = $member_coupons;
        $order_info['order_reduce'] = $ReduceRes['reduce'];
        return json(['status' => 1, 'msg' => '下单成功', 'order_info' => $order_info, 'money' => $member_wallet['money']]);
    }
}

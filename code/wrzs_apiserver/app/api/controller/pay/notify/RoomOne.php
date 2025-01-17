<?php


namespace app\api\controller\pay\notify;


use app\common\device\lock\LockFactory;
use app\common\kg\Kg;
use think\facade\Db;

class RoomOne
{
    static function index($order, $pay_type)
    {
        Db::name('order')->where(['order_id' => $order['order_id']])->update([
            'pay_status' => 1,
            'pay_time' => time(),
            'pay_type' => $pay_type,
            'order_status' => 1,
        ]);
        //查询设备
        $device = Db::name('device')->where([
            'room_id' => $order['room_id'],
            'device_type' => 1,
        ])->find();

        Db::name('member_pay_record')->insert([

            'title' => '购买单次房间',
            'created_at' => time(),
            'price' => $order['order_price'],
            'order_id' =>  $order['order_id'],
            'member_id'=>$order['member_id'],
            'type'=>2
        ]);
        if ($device) {
           $res = Kg::app()->device()->lock($device['device_sn'])->start();

            if (!$res) {
                Db::name('order')->where(['order_id' => $order['order_id']])->update([
                    'order_status' => 3,
                ]);
                Db::name('order_room')->where(['order_id' => $order['order_id']])->update([
                    'room_status' => 3,
                ]);

                //修改优惠券为使用
                $order_coupons = Db::name('order_coupons')->where([
                    'order_id' => $order['order_id']
                ])->find();
                if($order_coupons){
                    Db::name('member_coupons')->where([
                        'member_coupons_id' => $order_coupons['member_coupons_id']
                    ])->update(['use_time'=>time()]);
                }


            }

        }



    }
}

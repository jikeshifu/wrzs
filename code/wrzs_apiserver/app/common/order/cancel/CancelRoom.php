<?php


namespace app\common\order\cancel;


use app\common\cache\Redis;
use app\common\kg\Kg;
use app\common\member\MemberWallet;
use think\facade\Db;

class CancelRoom
{
    /**
     * @param array $order
     */
    static function room(array $order)
    {

        //开启事务
        Db::startTrans();
        try {
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

            //原路退还订单钱
            Refund::order($order);
            //查询是否有续费的订单
            $order_room_renew = Db::name('order_room_renew')->where([
                'pid' => $order['order_id'],
                'order_status' => 3
            ])->select()->toArray();
            if($order_room_renew){
                foreach ($order_room_renew as $vo){
                    //原路退还订单钱
                    $room_renew =Db::name('order')->where(['order_id'=>$vo['order_id']])->find();
                      Refund::order($room_renew,'取消续费订单');
                }
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            throw new \Exception($e->getMessage());


        }


    }
}

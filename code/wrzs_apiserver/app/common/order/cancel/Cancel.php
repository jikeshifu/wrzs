<?php


namespace app\common\order\cancel;


use think\facade\Db;

class Cancel
{
    /**
     * 订单取消
     * @param int $order_id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    static function order(int $order_id)
    {
        $order = Db::name('order')->where(['order_id' => $order_id])->find();
        switch ($order['order_type']){
            case 'roomOne':
            case 'room';
                CancelRoom::room($order);
            break;
        }

    }
}

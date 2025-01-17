<?php


namespace app\admin_api\controller\shop;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;

use app\common\order\cancel\Refund;
use think\facade\Db;
use think\facade\Request;

class Order
{

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $where = [
            'order_type' => 'goods',

        ];
        $type = input('post.type');
        if ($type == 1) {
            $where['order_status'] = 0;
        } elseif ($type == 2) {
            $where['order_status'] = [1, 2];
        } elseif ($type == 3) {
            $where['order_status'] = 3;
        } else {
            $where['order_status'] = 4;
        }
        $model = \app\model\order\Order::where($where)->with(['orderGoods','memberWechat','orderAddress']);
        $goods_name = input('post.goods_name');
        if ($goods_name) {
            $model->where('goods_name', 'like', '%' . $goods_name . '%');

        }
        $modelCount = clone $model;
        $count = $modelCount->count();
        $list = $model->page($page, $limit)->order(['order_id' => 'desc'])->select()->toArray();
        foreach ($list as &$listvo) {
            foreach ($listvo['orderGoods'] as &$orderGoodsVo) {
                $orderGoodsVo['goods_info'] = json_decode($orderGoodsVo['goods_info'], true);
            }
        }
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function deliver()
    {
        $order_id = input('order_id');
        try {
            Db::startTrans();
            //修改订单状态为发货
            Db::name("order")->where(['order_id' => $order_id])->update(['order_status' => 2]);
            //更新发货信息
            Db::name("order_address")->where(['order_id' => $order_id])->update([
                'deliver_sn' => input('deliver_sn'),
                'deliver_remarks' => input('deliver_remarks'),
            ]);


            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(ErrorCode::$adminCode[10]);
        }
        $res = SuccessCode::$statusOk;
        return json($res);
    }

    public function cancel()
    {
        $order_id = input('order_id');
        $order = Db::name("order")->where(['order_id' => $order_id])->find();
        try {
            Db::startTrans();
            //原路退还订单钱
            Refund::order($order);
            Db::name("order")->where(['order_id' => $order_id])->update([
                'order_status' => 4
            ]);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(ErrorCode::$adminCode[11]);
        }
        return json(SuccessCode::$statusOk);
    }
}

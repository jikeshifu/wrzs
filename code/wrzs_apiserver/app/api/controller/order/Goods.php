<?php


namespace app\api\controller\order;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\user\User;
use app\model\order\Order;
use think\facade\Db;

class Goods
{
    public function oderInfo()
    {
        $order_id = input('order_id');
        $order = Order::where(['order_id' => $order_id])->with(['orderGoods', 'orderAddress'])->find()->toArray();
        $res = SuccessCode::$statusOk;
        foreach ($order['orderGoods'] as &$vo) {

            $vo['goods_info'] = json_decode($vo['goods_info'], true);
        }
        $member_wallet = Db::name("member_wallet")->where(['member_id' => User::uid(),'store_id'=>$order['store_id']])->find();
        $res['order_info'] = $order;

        $res['money'] = $member_wallet['money'];
        return json($res);
    }

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $where = [
            'order_type' => 'goods',
            'member_id' => User::uid()
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
        $model = \app\model\order\Order::where($where)->with(['orderGoods']);
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

    /**
     * 确认收货
     *
     */
    public function takeDelivery()
    {
        $order_id =input('order_id');
        $order = Db::name("order")->where(["order_id" => $order_id])->find();
        if($order['order_status'] ==3){
            return json(SuccessCode::$statusOk);
        }
        if($order['order_status'] ==2){
            Db::name("order")->where(["order_id" => $order_id])->update(['order_status'=>3]);
            return json(SuccessCode::$statusOk);
        }

     return json(ErrorCode::$code[0]);
    }
}

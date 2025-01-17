<?php


namespace app\server_api\controller\order;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\Common;
use app\common\kg\Kg;
use app\common\member\MemberWallet;
use app\common\order\cancel\Refund;
use app\common\user\User;
use app\server_api\model\order\Order;
use think\facade\Db;
use think\facade\Request;


class Cabinet
{

    /**
     * @return \think\response\Json
     */
    public function list()
    {

        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $join_id = \app\common\user\User::uid();
        $Order = Order::where([
//            'join_id' => $join_id,
            'order_type' => "cabinet"
        ]);
        $Order->where(['order_status'=>[3,4]]);

        $mf_data = Request::param(['order_sn']);
        $Order = Common::search($Order, $mf_data);
        $jq_data = Request::param(['room_id', 'store_id', 'order_status', 'pay_status', 'pay_type']);
        $Order = Common::jqSearch($Order, $jq_data);
        $Ordercount = clone $Order;
        $count = $Ordercount->count();
        $list = $Order->with(['member','orderCabinet'])->page($page, $limit)->order(['order_id' => 'desc'])->select()->toArray();
        foreach ($list as &$vo){
            $vo['orderCabinet']['goods_info']=json_decode($vo['orderCabinet']['goods_info'],true);
        }
        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);

    }

    /**
     * 取消订单
     * */
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

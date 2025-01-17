<?php


namespace app\admin_h5_api\controller\order;


use app\common\Common;
use app\common\kg\Kg;
use app\common\member\MemberWallet;
use app\common\user\User;
use app\server_api\model\order\Order;
use think\facade\Db;
use think\facade\Request;

class Room
{
    public function list()
    {
        $adminH5UserInfo = User::adminH5UserInfo();
        $store_ids = json_decode($adminH5UserInfo['store_id_arr'], true);


        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
//        $join_id =$adminH5UserInfo['join_id'];
        $Order = Order::where([
//            'join_id' => $join_id,
            'order_type' => ['room','roomOne'],
            'store_id'=>$store_ids
        ]);
        $Order->where('order_status', '<>', 0);
        $Order->where('order_status', '<>', 5);
        $Order->where('order_status', '<>', 4);
        $Ordercount = clone $Order;
        $count = $Ordercount->count();
        $list = $Order->with(['orderRoom', 'member','store'])->page($page, $limit)->order(['order_id' => 'desc'])->select()->toArray();
        foreach ($list as &$vo) {
            $vo['orderRoom']['room_info'] = json_decode($vo['orderRoom']['room_info'], true);
            $vo['orderRoom']['room_info']['room_images'] = json_decode($vo['orderRoom']['room_info']['room_images']);
//            $vo['store']['work_week'] =json_decode($vo['store']['work_week']);
        }
        return json(['status' => 1, 'msg' => '操作成功', 'list' => $list, 'count' => $count]);
    }

    /**
     * 取消订单
     */
    public function cancel(){
        $order_id = input('post.order_id');
        $adminH5UserInfo = User::adminH5UserInfo();
        $join_id =$adminH5UserInfo['join_id'];
        $order = Db::name('order')->where(['order_id' => $order_id, 'join_id' => $join_id])->find();
        if(!$order){
            return json(['status' => 0, 'msg' => '订单超过不存在']);
        }
        if($order["created_at"]<time()-86400){
            return json(['status' => 0, 'msg' => '订单超过24小时不可取消']);
        }
        if ($order['order_status'] == 4) {
            return json(['status' => 0, 'msg' => '订单已取消']);
        }
        if ($order['pay_status'] != 1) {
            return json(['status' => 0, 'msg' => '订单未支付',"order"=>$order]);
        }

        if ($order['deposit_status'] == 2 || $order['deposit_status'] == 3  ) {
            if($order['deposit']>0){
                return json(['status' => 0, 'msg' => '退还过押金的订单不可取消']);
            }

        }
        \app\common\order\cancel\Cancel::order($order_id);
        return json(['status' => 1, 'msg' => '取消成功']);
    }

    /**
     * 退押金
     * */
    public function returnDeposit()
    {
        $data = Request::param(['order_id', 'deposit_deduct', 'deposit_remarks']);
        $adminH5UserInfo = User::adminH5UserInfo();
        $join_id =$adminH5UserInfo['join_id'];
        $Order = Order::where(['order_id' => $data['order_id'],'join_id'=>$join_id])->find()->toArray();
        if ($Order['order_status'] != 3) {
            return json(['status' => 0, 'msg' => '订单未完成']);
        }
        if ($Order['deposit_status'] == 3) {
            return json(['status' => 0, 'msg' => '押金已退回']);
        }
        $deposit = $Order['deposit'] - $data['deposit_deduct'];
        //如果要退还的押金大于0
        if ($deposit > 0) {
            //根据支付方式退还
            if ($Order['pay_type'] == "balance") {
                MemberWallet::increase($Order['member_id'], $deposit, '退押金', $Order['order_id']);
            } else {
                $pay_config = Db::name('pay_config')->where(['join_id' => $Order['join_id']])->find();
                $app = Kg::app()->watch()->payments($pay_config);
                // 参数分别为：商户订单号、商户退款单号、订单金额、退款金额、其他参数
                $refund = $app->refund->byOutTradeNumber($Order['order_sn'], $Order['order_sn'], $Order['order_price'] * 100, $deposit * 100, [
                    // 可在此处传入其他参数，详细参数见微信支付文档
                    'refund_desc' => '退还押金',
                ]);

                if ($refund['return_code'] != 'SUCCESS' || $refund['result_code'] != 'SUCCESS') {
                    return json(['status' => 0, 'msg' => '退款失败请配置支付证书', 'error' => $refund]);
                }

            }
        }
        Order::where(['order_id' => $data['order_id']])->save([
            'deposit_status' => 3,
            'deposit_deduct'=>$data['deposit_deduct'],
            'deposit_remarks'=>$data['deposit_remarks']
        ]);
        return json(['status' => 1, 'msg' => '退款成功']);

    }

}

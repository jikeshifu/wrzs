<?php


namespace app\server_api\controller\order;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\Common;
use app\common\kg\Kg;
use app\common\member\MemberWallet;
use app\common\user\User;
use app\server_api\model\order\Order;
use think\facade\Db;
use think\facade\Request;


class Room
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
            'join_id' => $join_id,
            'order_type' => ['room','roomOne']
        ]);
        $Order->where('order_status', '<>', 0);
        $Order->where('order_status', '<>', 5);
        $mf_data = Request::param(['order_sn']);
        $Order = Common::search($Order, $mf_data);
        $jq_data = Request::param(['room_id', 'store_id', 'order_status', 'pay_status', 'pay_type']);
        $Order = Common::jqSearch($Order, $jq_data);
        $Ordercount = clone $Order;
        $count = $Ordercount->count();
        $list = $Order->with(['orderRoom', 'member','store'])->page($page, $limit)->order(['order_id' => 'desc'])->select()->toArray();
        foreach ($list as &$vo) {
            $vo['orderRoom']['room_info'] = json_decode($vo['orderRoom']['room_info'], true);
            $vo['orderRoom']['room_info']['room_images'] = json_decode($vo['orderRoom']['room_info']['room_images']);

        }
        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);

    }

    /**
     * 退押金
     * */
    public function returnDeposit()
    {
        $data = Request::param(['order_id', 'deposit_deduct', 'deposit_remarks']);
        $Order = Order::where(['order_id' => $data['order_id']])->find()->toArray();
        if ($Order['order_status'] != 3) {


            return json( ErrorCode::errorF(['msg'=>'订单未完成']));
        }
        if ($Order['deposit_status'] == 3) {
            return json( ErrorCode::errorF(['msg'=>'押金已退回']));

        }
        $deposit = $Order['deposit'] - $data['deposit_deduct'];
        //如果要退还的押金大于0
        if ($deposit > 0) {
            //根据支付方式退还
            if ($Order['pay_type'] == "balance") {
                MemberWallet::increase($Order['member_id'], $deposit,  $Order['order_id'],$Order['store_id'],'退押金');
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
            'order_profit'=> $Order['order_profit'] -$deposit,
            'deposit_remarks'=>$data['deposit_remarks']
        ]);

        return json( SuccessCode::statusOkf(['msg'=>'退款成功']));


    }


}

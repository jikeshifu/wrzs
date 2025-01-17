<?php


namespace app\admin_h5_api\controller\join;


use app\common\code\SuccessCode;
use app\model\order\Order;
use think\facade\Db;

class Profit
{
    public function list()
    {
        $join_wallet = \app\server\join\Wallet::join_wallet();

//           $page = input('post.page', '1');

//        $limit = input('post.limit', '10');
        $end_time = input('post.end_time');
        $join_id =$join_wallet["join_id"];
        $start_time = input('post.start_time');
        if (!$start_time) {
            $start_time = strtotime(date("Y/m/") . '1');
        }
        $model = Order::where([
            'order_status' => 3,
            'join_id'=>$join_id,
            'pay_type' => 'wechat',
            'order_type' => ['recharge', 'rechargePackage', 'room', 'cabinet']
        ]);

        $model->where('created_at', '>', $start_time);

        if ($end_time) {
            $end_time=$end_time+86359;
            $model->where('created_at', '<', $end_time);
        }
        $profit = $model->sum('order_profit');
        $list = $model->select()->toArray();

//        $count = $model->count();
        $res = SuccessCode::$statusOk;

        $res['list'] = \app\server\join\Profit::orderType($list);
        $res['profit'] = $profit;

        $res['count'] = 0;
        return json($res);
    }
}

<?php


namespace app\server_api\controller\join;



use app\common\code\SuccessCode;

use app\model\order\Order;
use app\server\join\Profit;


class User
{




    public function profit()
    {

        $page = input('post.page', '1');

        $limit = input('post.limit', '10');
        $end_time = input('post.end_time');
        $join_id =\app\common\user\User::uid();
        $start_time = input('post.start_time');
        if (!$start_time) {
            $start_time = strtotime(date("Y/m/") . '1');
        }
        $model = Order::where([
            'order_status' => 3,
            'join_id'=>$join_id,
            'pay_type' => 'wechat',
            'order_type' => ['recharge', 'rechargePackage', 'room', 'cabinet']
        ])->page($page, $limit);

        $model->where('created_at', '>=', $start_time);

        if ($end_time) {
            $end_time=$end_time+86359;
            $model->where('created_at', '<=', $end_time);
        }
        $profit = $model->sum('order_profit');
        $list = $model->order("order_id desc")->select()->toArray();

        $count = $model->count();
        $res = SuccessCode::$statusOk;

        $res['list'] = Profit::orderType($list);
        $res['profit'] = $profit;

        $res['count'] = $count;
        return json($res);
    }
}

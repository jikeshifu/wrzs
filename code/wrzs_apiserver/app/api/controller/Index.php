<?php
declare (strict_types=1);

namespace app\api\controller;

use app\common\kg\Kg;
use app\common\sms\Manage;
use app\common\sms\Member;
use app\model\order\Order;
use app\module\pay\server\NotifyServer;
use app\server\store_admin\StoreAdmin;
use think\facade\Db;


class Index
{
    public function index()
    {
    //     $app = Kg::app()->watch()->payment();
    //   $res =  $app->order->queryByOutTradeNumber("202209201032269543198183831633");
    //     print_r($res);

    //     NotifyServer::Notify($res);
    }

    public function test()
    {

//        $join_wallet = Db::name("join_wallet")->where(["deleted_at" => 0])->select();
//        //获取前一天开始时间
//        $start_time = strtotime(date("Ymd")) - 86400;
//        //获取前一天开始时间
//        $end_time = $start_time + 86399;
//        $tody = date("Ymd", $start_time);
//
//        foreach ($join_wallet as $vo) {
//            if ($vo["profit_time"] != $tody) {
//
//                $price = Order::where("finish_time", "<", 1649347199)
//                    ->where([
//                        "order_status" => 3,
//                        "join_id" => $vo["join_id"],
//                        "pay_type"=>"wechat"
//                    ])->sum("order_profit");
//
//
////                //更新结算状态
////                Order::where("created_at", "between", [$start_time, $end_time])
////                    ->where([
////                        "order_status" => 3,
////                        "join_id" => $vo["join_id"],
////                        "pay_type"=>"wechat"
////                    ])->save(["js_status"=>1]);
//                if ($price > 0) {
//                    Db::name("join_wallet")
//                        ->where(["join_id" => $vo["join_id"]])
//                        ->update([
//                            "money" =>  $price,
//                            "money_total" => $price,
//                            "profit_time"=>$tody
//                        ]);
//                }
//            }
//
//        }
    }
}

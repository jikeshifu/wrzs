<?php


namespace app\admin_api\controller\order;

use app\common\order\cancel\Refund;
use app\model\member\MemberWallet;
use think\facade\Db;
use app\common\code\Code;

class Order
{
    static $order_status = [

        1 => "未开始",
        2 => "进行中",
        3 => "已完成",
        4 => "已取消",
    ];
    static $order_type = [

        "room" => "购买房间",
        "recharge" => "充值",
        "rechargePackage" => "充值套餐",
        "adminRecharge" => "后台充值",
        "roomRenew" => "用户续费",


    ];
    static $pay_type = [

        "balance" => "余额",
        "wechat" => "微信",
        "" => "系统",




    ];
    public function list()
    {

        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $model = \app\model\order\Order::order("order_id desc")->where("order_status",">",0);
        $order_sn=input("order_sn");
        if($order_sn){
            $model->where("order_sn","like","%{$order_sn}%");
        }
        $type=input("type");
        if($type==2){
            $model->where(["order_status"=>[1,2]]);
        }else if($type==3){
            $model->where(["order_status"=>3]);
        }
        $count = $model->count();
        $order = $model->with('memberWechat')->page($page, $limit)->select();
        foreach ($order as &$vo) {
            if(!$vo["pay_time"]){
                Db::name("order")->where(["order_id"=>$vo["order_id"]])->update(["pay_time"=>time()]);
            }
            $vo["order_status"] = self::$order_status[$vo["order_status"]];
            $vo["pay_type"] = self::$pay_type[$vo["pay_type"]];
            $vo["order_type"] = self::$order_type[$vo["order_type"]];



        }
        return json(Code::Ok(
            [
                "list" => $order,
                "count" => $count
            ]
        ));
    }


    public function cancel()
    {
        $order_id =input("order_id");
        $order=Db::name("order")->where(["order_id"=>$order_id])->find();
        if($order["created_at"]<(time()-(86400*7))){
            return json(Code::Err(["msg"=>"超过退款时间"]));
        }
       $res =  Refund::orderCancel($order);
       if($res){

           return json(Code::Err($res));
       }

        return json(Code::Ok());
    }

}

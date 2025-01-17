<?php


namespace app\module\pay\server;


use app\api\controller\pay\notify\Goods;
use app\api\controller\pay\notify\RechargePackage;
use app\api\controller\pay\notify\RoomOne;
use app\common\kg\Kg;
use app\module\member\server\MemberWechatServer;
use think\facade\Db;

class NotifyServer
{
    static function Notify($data){
        $order = Db::name('order')->where(['order_sn' => $data['out_trade_no']])->find();

        if ($order['pay_status'] == 1) {
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            exit;
        }
        Db::name('order')
            ->where(['order_sn' => $data['out_trade_no']])
            ->update([
                'transaction_id' => $data['transaction_id'],
                "pay_status" => 1,
                "pay_time" => time(),
                'pay_type' => "wechat",
            ]);

        //微信消费增加积分
        MemberWechatServer::integralPlus($order["member_id"],$order);


        $Beanstalkd = Kg::app()->Beanstalkd();
        switch ($order['order_type']) {
            case 'roomOne';
                RoomOne::index($order, 'wechat');
                break;
            case 'goods';
                Goods::index($order, 'wechat');
                break;
            case 'cabinet';
                $Beanstalkd->useTube('rrt-Cabinet')->put(json_encode(['order_id' => $order['order_id'], 'pay_type' => 'wechat']));
                break;
            case 'rechargePackage':
            case 'recharge';
                RechargePackage::index($order);
                break;
            default:
                $Beanstalkd->useTube('rrt-xdOrder')->put(json_encode(['order_id' => $order['order_id'], 'pay_type' => 'wechat']));
        }
    }
}

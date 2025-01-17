<?php

namespace app\api\controller\pay;

use app\api\controller\pay\notify\Goods;
use app\api\controller\pay\notify\Recharge;
use app\api\controller\pay\notify\RechargePackage;
use app\api\controller\pay\notify\RoomOne;
use app\common\cache\Redis;
use app\common\kg\Kg;
use app\module\member\server\MemberWechatServer;
use app\module\pay\server\NotifyServer;
use EasyWeChat\Factory;
use think\facade\Db;

class Notify
{
    public function index()
    {
        $xml = file_get_contents("php://input");
        $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        //查询订单信息

        if ($data['result_code'] == "SUCCESS" && $data['return_code'] == "SUCCESS") {
            NotifyServer::Notify($data);
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            exit;
        }


    }
}

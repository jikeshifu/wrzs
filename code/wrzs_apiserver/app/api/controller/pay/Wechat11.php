<?php


namespace app\api\controller\pay;

use app\common\kg\Kg;
use app\common\user\User;
use EasyWeChat\Factory;
use think\facade\Db;

class Wechat11
{
    public function index(){

        $config = [
            // 必要配置
            // 必要配置
            'app_id' => 'wxd4f039f4a5560e84',
            'mch_id' =>  '1603427239',
            'key' => 'ii3vT42d1HPurefzdRiUoi8UZoJ9hRh7',   // API 密钥

            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

            'notify_url'         => 'http://kj-server.weishequ.com/api/pay.Notify/index/',     // 你也可以在下单时单独设置来想覆盖它
        ];

        $app = Factory::payment($config);
        $result = $app->order->unify([
            'body' => '腾讯充值中心-QQ会员充值',
            'out_trade_no' => '20150806125346',
            'total_fee' => 88,
            'spbill_create_ip' => '111.230.97.87', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址

            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => 'ou0ed5SEDmqi4wtCroTH_zCYTQsg',
        ]);
        $result['time_stamp']=(string)time();

        $result['package']='prepay_id='. $result['prepay_id'];
        return json(['status' => 1, 'pay' => $result]);

    }
    public function index1()
    {
        $wxapp_id = input('post.wxapp_id');
        $order_id = input('post.order_id');
        $order = Db::name('order')->where(['order_id' => $order_id])->find();
        if ($order['pay_status'] == 1) {
            return json(['status' => 0, 'msg' => '订单已支付']);
        }
//        if ($order['created_at'] < time()-300) {
//            return json(['status' => 0, 'msg' => '订单超时请重新下单']);
//        }
        if ($order['order_type'] == 'room') {
            $body = '房间下单';
        }
        $app = Kg::app()->watch()->payment($wxapp_id);

        $result = $app->order->unify([
            'body' => $body,
            'out_trade_no' => $order['order_sn'],

            'total_fee' => $order['order_price'] * 100,
            'spbill_create_ip' => '111.230.97.87', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址

            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' =>User::userInfo()['openid'],

        ]);
        if($result['return_code']=='SUCCESS' && $result['result_code']=='SUCCESS'){
            $result['time_stamp']=(string)time();
            $result['package']='prepay_id='. $result['prepay_id'];
            return json(['status' => 1, 'pay' => $result]);

        }else{
            return json(['status' => 0, 'msg' => '支付调取失败','error'=>$result]);
        }

    }
}

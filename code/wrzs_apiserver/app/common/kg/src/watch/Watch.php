<?php


namespace app\common\kg\src\watch;


use app\common\cache\Redis;
use app\common\kg\src\cache\MyCustomCache;
use EasyWeChat\Factory;
use think\facade\Db;

class Watch
{
    function miniProgram()
    {
        $config = [
            'app_id' => 'wxf2978fb00bf48e65',
            'secret' => '5439f9dff699b9ec834ccfec0c72e99d',

        ];

        return  Factory::miniProgram($config);
    }


    function payment(){
        $config = [
            // 必要配置
            'app_id'             => 'wxf2978fb00bf48e65',
            'mch_id'             => '1667468102',
            'key'                => 'tlgmUwU9pvQ9bjBXflH7knUxjR4HAAoA',   // API 密钥

            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => '/www/wwwroot/was.weishequ.com/wrzs_apiserver/pay/wechat/apiclient_cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => '/www/wwwroot/was.weishequ.com/wrzs_apiserver/pay/wechat/apiclient_key.pem',      // XXX: 绝对路径！！！！

            'notify_url' => 'https://was.seeare.com/api/pay.Notify/index',  // 异步通知地址
        ];


        return Factory::payment($config);
    }
}

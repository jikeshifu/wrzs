<?php


namespace app\common\kg\src\sms;


use app\common\curl\Curl;

class Sms
{
    function yemeisms($arr)
    {
        if (!is_array($arr) || !$arr) {
            return false;
        }
        if (!isset($arr['content']) || !$arr['content']) {
            return false;
        }
        if (!isset($arr['mobiles'])) {
            return false;
        }

        $urlarr = ['bjmtn.b2m.cn','shmtn.b2m.cn','bjksmtn.b2m.cn','gzmtn.b2m.cn:8080'];
        $appid = '1111-111-1111-11111'; // 亿美APPid
        $secretkey = '111111111111111'; // 秘钥
        $content = '';/* 短信内容请以商务约定的为准，如果已经在通道端绑定了签名，则无需在这里添加签名 */
        $timestamp = date("YmdHis");
        $sign = md5($appid.$secretkey.$timestamp);
        $data = [];
        $data['appId'] = $appid;
        $data['timestamp'] = $timestamp;
        $data['sign'] = $sign;
        $data = array_merge($data,$arr);
        $urlk = mt_rand(0,3);
        $url = $urlarr[$urlk].'/simpleinter/sendSMS';


        return Curl::curl_request($url,'get',$data);
    }

}

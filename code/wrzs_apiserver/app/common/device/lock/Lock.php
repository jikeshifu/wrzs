<?php


namespace app\common\device\lock;




use app\common\curl\Curl;
use app\common\device\DeviceConfig;

class Lock
{

    public $data = [];
    public $mqttUrl = "http://mqtt.suo.link/api/";
    public $kg_url = 'https://www.kuaige.com/api/';
    public $wmj_url = 'https://www.wmj.com.cn/api/';
    function __construct()
    {

        $this->data['appid'] = DeviceConfig::$appId;
        $this->data['appsecret'] =  DeviceConfig::$appSecret;

    }
    public function oplock($sn){
        $this->data['sn'] = $sn;
        $url = $this->wmj_url . '/oplock';
        $res = json_decode(Curl::posturl($url, $this->data),true);
        if ($res['state'] == 1) {

            return ['status' => true, 'msg' => '开门成功'];
        } else {
            return ['status' => false, 'msg' => '开门失败-' . $res['state_msg']];
        }
    }
    public function reglock($sn)
    {
        $this->data['sn'] = $sn;
        $url = $this->wmj_url . '/reglock';

        $res = json_decode(Curl::posturl($url, $this->data),true);


        if ($res['state'] == 1 || $res['state_code'] == 1004) {
            $reggw = json_decode(Curl::posturl($this->mqttUrl . '/Equipment.index/reggw', $this->data), true);
            if ($reggw['status'] == 0) {
                return ['status' => false, 'msg' => '添加失败-' . $reggw['msg']];
            }
            return ['status' => true, 'msg' => '添加成功'];
        } else {
            return ['status' => false, 'msg' => '添加失败-' . $res['state_msg']];
        }

    }

    public function lcdconfig($sn, $qrcodeurl)
    {
        $this->data['qrcodeurl'] = $qrcodeurl;
        $this->data['sn'] = $sn;
        $result = json_decode(Curl::posturl($this->wmj_url . "/lcdconfig", $this->data),true);
        if ($result['state'] ==1) {
            return ['status' => true, 'msg' => '设置成功'];
        }
        return ['status' => false, 'msg' => $result['state_msg']];
    }
    public function audioconfig($sn,$openttscontent,$volume){
        $this->data['openttscontent'] = $openttscontent;
        $this->data['volume'] = $volume;
        $this->data['sn'] = $sn;
        $result = json_decode(Curl::posturl($this->wmj_url . "/audioconfig", $this->data),true);
        if ($result['state'] ==1) {
            return ['status' => true, 'msg' => '设置成功'];
        }
        return ['status' => false, 'msg' => $result['state_msg']];
    }
}

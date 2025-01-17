<?php


namespace app\common\kg\src\device;




use app\common\curl\Curl;
use app\common\device\DeviceConfig;

class Lock
{

    public $data = [];
    public $mqttUrl = "http://mqtt.suo.link/api/";
    public $kg_url = 'https://www.kuaige.com/api/';
    public $wmj_url = 'https://www.wmj.com.cn/api/';

    function __construct($sn)
    {
        $this->url = DeviceConfig::$device_url;
        $this->data['sn'] = $sn;
        $this->data['account'] = DeviceConfig::$account;
        $this->data['password'] =  DeviceConfig::$password;

    }



    public function qr($qrcodeurl)
    {
        $this->data['qr'] = $qrcodeurl;

        $result = Curl::postUrlArray($this->url . "/device.Lock/qr", $this->data);

        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];

    }


    public function cardAdd($card_sn,$end_time)
    {
        $this->data['card_sn'] = $card_sn;
        $this->data['end_time'] = $end_time;
        $result = Curl::postUrlArray($this->url . "/device.Card/add", $this->data);

        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];

    }

    public function cardDel($card_sn)
    {
        $this->data['card_sn'] = $card_sn;

        $result = Curl::postUrlArray($this->url . "/device.Card/del", $this->data);

        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];

    }
    public function voice($openttscontent,$volume){

        $this->data['voice'] = $openttscontent;
        $this->data['volume'] = $volume;
        $result = Curl::postUrlArray($this->url. "/device.Lock/voice", $this->data);
        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];

    }

    public function wgStart($gw_sn)
    {
        $this->data['gw_sn'] = $gw_sn;
        $result = json_decode(Curl::posturl($this->url. "/device.Gateway/start", $this->data),true);
        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];
    }

    public function start()
    {

        $result = json_decode(Curl::posturl($this->url. "/device.Lock/start", $this->data),true);
        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];
    }


}

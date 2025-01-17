<?php

namespace app\common\kg\src\device;

use app\common\curl\Curl;
use app\common\device\DeviceConfig;

class AirSwitch
{
    public $url ='';
    public $data = [];

    public function __construct($sn)
    {
        $this->url = DeviceConfig::$device_url;
        $this->data['sn'] = $sn;
        $this->data['account'] = DeviceConfig::$account;
        $this->data['password'] =  DeviceConfig::$password;


    }


    public function start($volume=5,  $msg = '欢迎光临，已为您打开电源')
    {

        $this->data['volume'] = $volume;
        $this->data['msg'] = $msg;


        $result = Curl::postUrlArray($this->url . "/device.AirSwitch/start", $this->data);

        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];

    }

    public function regswitch()
    {
        $result = Curl::postUrlArray($this->url . "/device.Device/register", $this->data);
        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];

    }



    public function stop($volume=5,  $msg = '电源已关闭')
    {

        $this->data['volume'] = $volume;
        $this->data['msg'] = $msg;

        $result = Curl::postUrlArray($this->url . "/device.AirSwitch/stop", $this->data);

        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];
    }
}

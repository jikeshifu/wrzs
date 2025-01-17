<?php

namespace app\common\kg\src\device;

use app\common\curl\Curl;
use app\common\device\DeviceConfig;

class Cabinet
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

    /**
     * @param $line
     * @return bool|mixed
     */
    public function open($line)
    {


        $this->data['line'] = $line;

        $result = Curl::postUrlArray($this->url . "/device.Cabinet/openLock", $this->data);

        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];

    }

    /**
     * @return bool|mixed
     */
    public function state()
    {

        $result = Curl::postUrlArray($this->url . "/device.Cabinet/state", $this->data);

        if($result['error_code']==0 ){
            return $result;
        }
        return false;

    }


}

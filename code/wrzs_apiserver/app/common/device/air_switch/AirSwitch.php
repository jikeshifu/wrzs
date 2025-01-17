<?php


namespace app\common\device\air_switch;


use app\common\curl\Curl;
use app\common\device\DeviceConfig;

class AirSwitch
{
    public $url ='';
    public $data = [];

    public function __construct($sn)
    {
        $this->url = DeviceConfig::$device_url;
        $this->data['account'] = DeviceConfig::$account;
        $this->data['password'] =  DeviceConfig::$password;

    }


    public function start($volume, $msg = '欢迎光临，已为您打开电源')
    {

        $this->data['volume'] = $volume;
        $this->data['msg'] = $msg;
        $result = Curl::postUrlArray($this->url . "/device.AirSwitch/stop", $this->data);

        if($result['state']==1 ){
            return ['status'=>true];
        }
        return ['status'=>false,'msg'=>$result['state_msg']];
    }

    public function stop($volume, $msg = '欢迎光临，已为您打开电源')
    {

        $this->data['volume'] = $volume;
        $this->data['msg'] = $msg;
        $result = Curl::postUrlArray($this->url . "/device.AirSwitch/stop", $this->data);

        if($result['state']==1 ){
            return ['status'=>true];
        }
        return ['status'=>false,'msg'=>$result['state_msg']];
    }

    public function regswitch()
    {
        $result = Curl::postUrlArray($this->url . "/device.Device/register", $this->data);

        if($result['state']==1 || $result['state_code']==1004){
            return ['status'=>true];
        }

        return ['status'=>false,'msg'=>$result['state_msg']];

    }


}

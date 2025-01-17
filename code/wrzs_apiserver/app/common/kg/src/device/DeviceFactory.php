<?php


namespace app\common\kg\src\device;


use app\common\curl\Curl;
use app\common\device\DeviceConfig;

class DeviceFactory
{

    public function airSwitch($sn)
    {
        return new AirSwitch($sn);

    }

    public function lock($sn)
    {
        return new Lock($sn);
    }

    public function onLine($sn)
    {
        $data['account'] = DeviceConfig::$account;
        $data['password'] = DeviceConfig::$password;
        $data['sn'] = $sn;
        $result = Curl::postUrlArray(DeviceConfig::$device_url . "/device.Device/onLine", $data);
        if ($result['error_code']==0) {
            return 1;
        }
        return 0;
    }

    public function cabinet($sn){
        return new Cabinet($sn);
    }
    public function register($sn)
    {
        $data['account'] = DeviceConfig::$account;
        $data['password'] = DeviceConfig::$password;
        $data['sn'] = $sn;
        $result = Curl::postUrlArray(DeviceConfig::$device_url . "/device.Device/register",$data);
        if($result['error_code']==0 ){
            return false;
        }
        return $result['msg'];
    }
}

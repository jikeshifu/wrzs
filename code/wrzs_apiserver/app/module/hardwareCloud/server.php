<?php


namespace app\module\hardwareCloud;


use app\common\curl\Curl;

class server
{
    /**
     * @param $device_sn
     * 注册设备
     */
    public function Register($device_sn)
    {
        $res = self::Request("mqtt/register", ["device_sn" => $device_sn]);
        if ($res["code"] != 0 && $res["code"] != 1005) {
            return ["err" => $res["msg"]];
        }


        return ["err" => null];
    }

    /**
     * @param $device_sn
     * 获取设备在线状态
     */
    public function OnLineGet($device_sn)
    {
        $res = self::Request("mqtt/getOnLine", ["device_sn" => $device_sn]);
        if ($res["code"] != 0) {
            return 0;
        }


        return $res["data"]["on_line"];
    }

    /**
     * @param $path
     * @param array $data
     * @return int|mixed|string
     * 请求
     */
     public static function Request($path, $data = [])
    {
        $data["app_id"] = serverConfig::GetAppId();
        $data["app_secret"] =  serverConfig::GetAppSecret();
        $res = Curl::PostJson(serverConfig::GetUrl() . $path, $data);

        return $res;
    }
    /**
     * 注销设备
     */

    public function Logout($device_sn)
    {
        $res = self::Request("mqtt/logout", ["device_sn" => $device_sn]);
        if ($res["code"] != 0 && $res["code"] != 1005) {
            return ["err" => $res["msg"]];
        }


        return ["err" => null];
    }



}

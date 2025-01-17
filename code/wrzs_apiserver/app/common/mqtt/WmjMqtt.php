<?php


namespace app\common\mqtt;


class WmjMqtt
{
    static public $mqtt = null;


    private function __construct()
    {
    }

    static function mqtt()
    {
        if (self::$mqtt) {

            return self::$mqtt;
        }

        $client = new \Mosquitto\Client();
        $client->setCredentials('1233', '12344561');

        $client->connect("iot.wmj.com.cn", 1883, 5);
        self::$mqtt = $client;
        return self::$mqtt;
    }


}

<?php


namespace app\module\hardwareCloud;


class serverConfig
{
    //默认对接硬件云配置
    static $WiFIUrl = "https://wdev.wmj.com.cn/deviceApi/";
    static $AppId = "bc2bc50e5f2239b38f9edb11997ad464";
    static $AppSecret = "f178ddefcc7c9075f14dab84c137fff8";

    static function GetUrl(){
        return self::$WiFIUrl;
    }

    static function GetAppId(){
        return self::$AppId;
    }

    static function GetAppSecret(){
        return self::$AppSecret;
    }

}

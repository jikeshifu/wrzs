<?php


namespace app\common\cache;


class Redis
{
    static public $redis = null;
    private function __construct()
    {


    }

    static function redis()
    {
        if (self::$redis) {

            return self::$redis;
        }
        $Redis = new \Redis();

        $Redis->connect('127.0.0.1', 6379);

        $Redis->auth('seeare@password');
        $Redis->select(11);
        self::$redis = $Redis;
        return self::$redis;
    }
}

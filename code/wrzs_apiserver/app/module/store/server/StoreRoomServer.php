<?php


namespace app\module\store\server;


use app\common\cache\Redis;
use app\module\server\Server;

class StoreRoomServer extends Server
{
    static function timeSlot($room_id, $start_time, $end_time)
    {

        $redis = Redis::redis();

        foreach ($redis->hGetAll('room_id:' . $room_id) as $key => $vo) {
            $arr = json_decode($vo, true);

            if ($start_time >= $arr['start_time'] && $start_time <= $arr['end_time']) {
                self::$error = "当前时间段不可使用,请重新下单";
                return;
            }
            if ($end_time >= $arr['start_time'] && $start_time <= $arr['end_time']) {
                return;
                self::$error = "当前时间段不可使用,请重新下单";
            }

        }
    }
}
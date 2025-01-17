<?php


namespace app\common\kg\src\room;


use app\common\cache\Redis;

class Room
{
    public function timeSlot($room_id,$start_time,$end_time){

        $redis = Redis::redis();

        foreach ( $redis->hGetAll('room_id:' . $room_id) as $key=>$vo){
            $arr =json_decode($vo,true);

            if( $start_time>= $arr['start_time']  &&  $start_time<= $arr['end_time']){
                echo json_encode( [
                    'status' => 0,
                    'msg' => '当前时间段不可使用,请重新下单',
                    'data'=>$arr,
                    'order_id'=>$key
                ]);
               die;
            }
            if( $end_time>= $arr['start_time']  &&  $start_time<= $arr['end_time']){
                echo json_encode( [
                    'status' => 0,
                    'msg' => '当前时间段不可使用,请重新下单',
                    'data'=>$arr,
                    'order_id'=>$key
                    ]);
                die;
            }

        }
    }
}

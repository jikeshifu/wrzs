<?php


namespace app\api\controller\room;


use app\common\cache\Redis;
use app\common\code\SuccessCode;
use think\facade\Db;
class Room
{
    public function timeSlot()
    {
        $room_id = input('post.room_id');
        $redis = Redis::redis();
        $timeArray =[];
        $romm = Db::name('room')->where(['room_id' => $room_id])->find();
        $todayTime = time();
        $redisKey = 'room_id:' . $room_id;
        if($romm['reserve_status']==0){
            foreach ($redis->hGetAll($redisKey) as $key => $vo) {
                $arr = json_decode($vo, true);

                //如果时间小于当前时间减去一天就清除缓存
                if ($arr["end_time"] < ($todayTime - 86400)) {
                    $redis->hDel($redisKey, $key);
                } else {
                    $timeArray[] = $arr;
                }
            }
        }
        $res =SuccessCode::$code[0];
        $res['list']=$timeArray;
        return json($res);
    }

}

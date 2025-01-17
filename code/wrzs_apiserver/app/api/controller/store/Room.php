<?php


namespace app\api\controller\store;


use app\common\cache\Redis;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\file\FileYs;
use app\common\user\User;
use think\facade\Db;

class Room
{
    public function info()
    {
        $room_id = input('post.room_id');
        $Room = \app\server_api\model\store\Room::where(['room_id' => $room_id])->with('store')->find()->toArray();
        $Room['room_images']=json_decode($Room['room_images'],true);

        foreach ($Room['room_images'] as &$vo){
            $vo = FileYs::getThumb($vo, '1136', '640');
        }


        return json(SuccessCode::statusOkf([
            'room_info'=>$Room,
            'asd'=>123,
        ]));
    }

    public function list()
    {
        $store_id = input('post.store_id');
        $latitude = input('post.latitude');
        $longitude = input('post.longitude');
        $list = Db::name('room')->where(['store_id' => $store_id, 'deleted_at' => 0, 'status' => 1])->order("sort desc")->select()->toArray();
        $res = SuccessCode::$code[0];
        try {
            foreach ($list as &$vo) {
                $vo['room_image'] = FileYs::getThumb($vo['room_image'], '1136', '640');
                $vo['room_images'] = json_decode($vo['room_images'], true);
                if (is_array($vo['room_images'])) {
                    foreach ($vo['room_images'] as &$room_imagesVo) {
                        $room_imagesVo = FileYs::getThumb($room_imagesVo, '1136', '640');
                    }
                }
            }
            $res['list'] = $list;
            $res['store_info'] = Db::name('store')->where(['store_id' => $store_id])->find();
            $res['store_info']['store_image']= FileYs::getThumb($res['store_info']['store_image'], '1136', '640');
            $member_id = User::uid();
            if ($latitude && $longitude) {
                $redis = Redis::redis();
                $redis->geoadd('geo:store', $longitude, $latitude, 'u' . $member_id);
                $res['store_info']['distance'] = (float)substr($redis->geodist('geo:store', $vo['store_id'], 'u' . $member_id, 'km'), 0, -1);
                $redis->zRem('geo:store', 'u' . $member_id);
            }
        } catch (\Exception $e) {
           return json(ErrorCode::errorF(['msg'=>$e->getMessage()]));
        }
        return json($res);
    }
}

<?php


namespace app\module\store\server;


use app\common\cache\Redis;
use app\common\code\SuccessCode;
use app\common\file\FileYs;
use app\common\user\User;
use app\server\join\Wallet;
use think\facade\Db;

class StoreServer
{
    static function Del($user_id)
    {
        //删除用户
        Db::name("join_user")->where(['user_id' => $user_id])->update([
            'deleted_at' => time(),
            'deleted_uid' => \app\common\user\User::uid(),
        ]);
        $del=['deleted_at' => time()];
        //删除门店
        Db::name("store")->where(['join_id' => $user_id])->update($del);
        //删除房间
        Db::name("room")->where(['join_id' => $user_id])->update($del);
        //删除钱包
        Wallet::del($user_id);
    }

    /**
     * @param $longitude 经度
     * @param $latitude 纬度
     * @param string $city 城市
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
   static  public function Nearby($longitude,$latitude,$city="")
    {
        $redis = Redis::redis();
        $km = 100;
        if ($city) {
            $km = 2500;
        }
        //获取范围内的数据
        $geores = $redis->georadius('geo:store', $longitude, $latitude, $km, 'km');
        $member_id = User::uid();
        $memberKey ="u" . $member_id;
        //添加用户所在位置
        $redis->geoadd('geo:store', $longitude, $latitude, $memberKey);
        if (!$geores) {
            $res = SuccessCode::$code[0];
            $res['list'] = [];
            return $res;
        }
        foreach ($geores as $key => $vo) {
            if (!is_numeric($vo)) {
                unset($geores[$key]);
            }
        }

        $res = SuccessCode::$code[0];
        $res['geores'] = $geores;
        $where = [
            'deleted_at' => 0,
            "status"=>1,
            'store_id' => $geores,
        ];
        $store_type = input("store_type");
        if ($store_type) {
            $where["store_type"] = $store_type;
        }
        $store_id_zfc = json_encode($where);
        $storekey = base64_encode($store_id_zfc . $city);
        $list = $redis->get($storekey);
        if (!$list) {
            $Store = \app\server_api\model\store\Store::where($where)->with(['roommin']);
            $list = $Store->select()->toArray();
            foreach ($list as $key => &$vo) {
                $vo['store_image'] = FileYs::getThumb($vo['store_image'], '1136', '640');
                //计算用户与门店的距离
                $vo['distance'] = (float)substr($redis->geodist('geo:store', $vo['store_id'], $memberKey, 'km'), 0, -1);
                if (strlen($vo['room_price'])==0) {
                    unset($list[$key]);
                }
            }
            //保存60-100秒的缓存
            $redis->setex($storekey, rand(60, 100), json_encode($list));
        } else {
            $list = json_decode($list, true);
        }
        $redis->zRem('geo:store', $memberKey);
//        foreach ($list as &$vo1){
//            $map = Map::Convert_BD09_To_GCJ02( $vo1['latitude'],$vo1['longitude']);
//            $vo1['longitude'] = (float)$map['lng'];
//            $vo1['latitude'] = (float)$map['lat'];
//        }

        $data = array_column($list, 'distance');
        array_multisort($data, SORT_ASC, $list);
        $res['list'] = $list;
        return $res;
    }

}
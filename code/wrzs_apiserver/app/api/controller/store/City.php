<?php


namespace app\api\controller\store;


use app\common\code\SuccessCode;
use app\common\map\Map;
use think\facade\Db;

class City
{
    public function list()
    {
        $where = [
            'deleted_at' => 0
        ];
        $city = Db::name('city')->where($where)->cache(true, 60)->select()->toArray();
        $where['is_default'] = 1;
        $res = SuccessCode::$code[0];
        $res['list'] = $city;
//        $res['default'] = Db::name('city')->where($where)->cache(true, 60)->find();
        foreach ($res['list'] as &$vo) {
            $map = Map::coordinate_switch( $vo['latitude'],$vo['longitude']);
            $vo['longitude'] = $map['Longitude'];
            $vo['latitude'] = $map['Latitude'];
        }
//        $map = Map::coordinate_switch( $res['default']['latitude'],$res['default']['longitude']);
//        $res['default']['longitude']=$map['Longitude'];
//        $res['default']['latitude']= $map['Latitude'];
        return json($res);
    }
}

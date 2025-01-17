<?php


namespace app\api\controller\store;


use app\common\cache\Redis;
use app\common\code\SuccessCode;
use app\common\file\FileYs;
use app\common\kg\Kg;
use app\common\map\Map;
use app\common\user\User;
use app\module\store\server\StoreServer;
use think\facade\Db;

class Store
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '100');
        $where = [

            'deleted_at' => 0

        ];
        $Store = \app\server_api\model\store\Store::where($where);

        $store_name = input('post.store_name');
        if ($store_name) {
            $Store->where('store_name', 'like', '%' . $store_name . '%');
        }
        $city = input('post.city');
        if ($city && $city != '全国') {
            $Store->where(['city' => $city]);
        }
        $Storecount = clone $Store;

        $list = $Store->page($page, $limit)->cache(true, 60)->select()->toArray();


        return json(SuccessCode::statusOkf(['status' => 1, 'msg' => '操作成功', 'list' => $list, 'count' => $Storecount->cache(true, 900)->count()]));

    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 附近门店
     */
    public function nearby()
    {

        //经度
        $longitude = (float)input('post.longitude');
        //纬度
        $latitude = (float)input('post.latitude');
        //城市
        $city = input('post.city');
        $res = StoreServer::Nearby($longitude, $latitude, $city);
        return json($res);

    }


}

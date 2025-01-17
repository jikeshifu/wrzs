<?php


namespace app\server_api\controller\store;


use app\common\cache\Redis;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\data\Data;
use app\common\log\AdminLog;
use app\common\user\User;
use app\server_api\controller\Base;
use think\facade\Db;

class Store extends Base
{
    public $file_ld = [
        'store_name',
        'contact',
        'district',
        'province',
        "label",
        'city',
        'longitude',
        'latitude',
        'store_about',
        'store_image',
        'store_type',
        'work_time_start',
        'work_time_end',
        'work_week',
        'address',
        "vid"
    ];

    public function add()
    {
        $data = Data::clearEmpty($this->request->only($this->file_ld));
        $join_id = User::uid();
        $store = Db::name('store')->where([
            'store_name' => $data['store_name'],
            'deleted_at' => 0,
            'join_id' => $join_id
        ])->find();
        if ($store) {
            $errRes = ErrorCode::$adminCode[3];
            return json($errRes);
        }

        if (!isset($data['longitude']) || !$data['longitude']) {
            $errRes = ErrorCode::$adminCode[14];
            return json($errRes);
        }
        $join_user = Db::name("join_user")->where(['user_id' => $join_id])->find();
        $storeCount = Db::name('store')->where(['join_id' => $join_id, 'deleted_at' => 0])->count();

        if ($storeCount >= $join_user['store_number']) {
            $errRes = ErrorCode::$adminCode[15];
            return json($errRes);
        }
        $data['join_id'] = $join_id;

        $store_id = Db::name('store')->insertGetId($data);
        $redis = Redis::redis();
        $redis->geoadd('geo:store', $data['longitude'], $data['latitude'], $store_id);

        return json(SuccessCode::$statusOk);
    }

    public function edit()
    {
        $data = Data::clearEmpty($this->request->only($this->file_ld));

        $store_id = input('post.store_id');
        $where['store_id'] = $store_id;

        $redis = Redis::redis();

        $redis->geoadd('geo:store', $data['longitude'], $data['latitude'], $store_id);

        Db::name('store')->where($where)->update($data);

        return json(SuccessCode::$statusOk);
    }

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $public = input('post.public');
        $join_id = User::uid();
        $where = [
            'deleted_at' => 0,
            'join_id' => $join_id
        ];
        $store = Db::name('store')->where($where);
        $status = input('post.status');

        if (strlen($status) > 0) {
            $store->where(['status' => $status]);
        }

        if ($public) {
            $store->where(function ($query) use ($public) {
                $query->where('store_name', 'like', '%' . $public . '%')
                    ->whereOr('contact', 'like', '%' . $public . '%');
            });
        }

        $admin_user_count = clone $store;
        $count = $admin_user_count->count();
        $list = $store->page($page, $limit)->select()->toArray();

        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function del()
    {
        $store_id = input('post.store_id');
        $redis = Redis::redis();
        $redis->zRem('geo:store', $store_id);
        if ($store_id) {
            Db::name('store')->where(['store_id' => $store_id])->update(['deleted_at' => time()]);
            Db::name('room')->where(['store_id' => $store_id])->update(['deleted_at' => time()]);
        }
        return json(SuccessCode::$statusOk);
    }

    public function status()
    {
        $store_id = input('post.store_id');
        $status = input('post.status');
        if ($store_id) {
            Db::name('store')->where(['store_id' => $store_id])->update(['status' => $status]);
            Db::name('room')->where(['store_id' => $store_id])->update(['status' => $status]);
        }
        return json(['status' => 1, 'msg' => '操作成功']);
    }
}

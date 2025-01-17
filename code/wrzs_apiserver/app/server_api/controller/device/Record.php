<?php


namespace app\server_api\controller\device;


use app\common\code\SuccessCode;
use app\common\user\User;
use think\facade\Db;

class Record
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $public = input('post.public');
        $join_id = User::uid();
        $where = [
            'join_id'=> $join_id
        ];
        $device = Db::name('device_record')->where($where);
        if ($public) {
            $device->where(function ($query) use ($public) {
                $query->whereOr('username', 'like', '%' . $public . '%')
                    ->whereOr('device_sn', 'like', '%' . $public . '%')
                    ->whereOr('device_name', 'like', '%' . $public . '%')
                    ->whereOr('room_name', 'like', '%' . $public . '%')
                    ->whereOr('title', 'like', '%' . $public . '%');
            });
        }
        $admin_user_count = clone $device;
        $count = $admin_user_count->count();
        $list = $device->page($page, $limit)->order(['record_id' => 'desc'])->select()->toArray();
        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);

    }
}

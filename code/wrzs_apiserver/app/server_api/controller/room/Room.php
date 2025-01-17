<?php


namespace app\server_api\controller\room;


use app\common\code\SuccessCode;
use app\common\data\Data;
use app\common\file\FileYs;
use app\common\log\AdminLog;
use app\common\user\User;
use app\server\room\RoomExtend;
use app\server_api\controller\Base;
use think\facade\Db;

class Room extends Base
{
    public $file_ld = [
        'room_name',
        'room_images',
        'room_image',
        'room_type',
        'room_people',
        'room_price',
        'room_about',
        'room_sold',
        'room_deposit',
        'sort',
        'room_size',
        'number_people',
        'store_id',
        'room_label',
        'reserve_status',
        'electric_status',
        'starting_time',
        'work_time_end',
        'work_time_start',
        'work_week',
        'electric_stop_status',
        'cancel_status',
        'dm_status'

    ];

    public function add()
    {

        $data = Data::clearEmpty($this->request->only($this->file_ld));
        $join_id = User::uid();

        $store = Db::name('room')->where([
            'room_name' => $data['room_name'],
            'deleted_at' => 0,
            'join_id' => $join_id
        ])->find();
        if ($store) {
            return json(['status' => 0, 'msg' => '房间名称重复']);
        }
        $data['work_week'] = json_encode($data['work_week']);
        $data['join_id'] = $join_id;
        $data['room_images'] = json_encode($data['room_images']);
        $room_id = Db::name('room')->insertGetId($data);
        RoomExtend::add(["room_id" => $room_id]);
        return json(SuccessCode::$statusOk);
    }

    public function edit()
    {

        $data = Data::clearEmpty($this->request->only($this->file_ld));

        $room_id = input('post.room_id');
        $where['room_id'] = $room_id;

        $data['electric_status'] = input('post.electric_status');
        $data['reserve_status'] = input('post.reserve_status');
        $data['room_deposit'] = input('post.room_deposit');
        $data['electric_stop_status'] = input('post.electric_stop_status');
        $data['work_week'] = json_encode($data['work_week']);
        $data['room_images'] = json_encode($data['room_images']);

        Db::name('room')->where($where)->update($data);
        RoomExtend::edit(["room_id" => $room_id]);

       $room_solds = Db::name('room')->where(["store_id"=>$data['store_id']])->sum("room_sold");
        Db::name('store')->where(["store_id"=>$data['store_id']])->update(["store_sold"=>$room_solds]);
        $res = SuccessCode::$statusOk;
        $res["data"]=$data;
        return json($res);
    }

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $public = input('post.public');
        $where = [
            'deleted_at' => 0,
        ];
        $room = \app\server_api\model\store\Room::where($where);
        $store_id = input('post.store_id');

        if ($store_id) {
            $room->where(['store_id' => $store_id]);
        }
        $status = input('post.status');
        if (strlen($status) > 0) {
            $room->where(['status' => $status]);
        }
        if ($public) {
            $room->where(function ($query) use ($public) {
                $query->where('room_name', 'like', '%' . $public . '%');

            });
        }
        $admin_user_count = clone $room;
        $count = $admin_user_count->count();
        $list = $room->with("roomExtend")->page($page, $limit)->order(['sort' => 'desc', 'room_id' => 'desc'])->select()->toArray();
        foreach ($list as &$vo) {
            $vo['room_images'] = json_decode($vo['room_images'], true);
            $vo['work_week'] = json_decode($vo['work_week'], true);

            $vo['room_image'] = FileYs::getThumb($vo['room_image'], 1200, 800);

        }

        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);

    }

    public function del()
    {
        $room_id = input('post.room_id');
        if ($room_id) {

            Db::name('room')->where(['room_id' => $room_id])->update(['deleted_at' => time()]);
            Db::name('device')->where(['room_id' => $room_id])->update(['deleted_at' => time()]);
        }
        $res = SuccessCode::$statusOk;

        return json($res);
    }

    public function status()
    {
        $room_id = input('post.room_id');
        $status = input('post.status');
        if ($room_id) {

            Db::name('room')->where(['room_id' => $room_id])->update(['status' => $status]);
        }
        $res = SuccessCode::$statusOk;

        return json($res);
    }

    public function sort()
    {
        $room_id = input('post.room_id');
        $sort = input('post.sort');
        if ($room_id) {

            Db::name('room')->where(['room_id' => $room_id])->update(['sort' => $sort]);
        }
        $res = SuccessCode::$statusOk;

        return json($res);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * electric_stop_status
     */
    public function electricStartStatus()
    {
        $electric_status = input('post.electric_status', 0);
        $room_id = input('post.room_id');
        if ($room_id) {

            Db::name('room')->where(['room_id' => $room_id])->update(['electric_status' => $electric_status]);
        }
        $res = SuccessCode::$statusOk;

        return json($res);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * electric_stop_status
     */
    public function electricStopStatus()
    {
        $electric_stop_status = input('post.electric_stop_status', 0);
        $room_id = input('post.room_id');
        if ($room_id) {

            Db::name('room')->where(['room_id' => $room_id])->update(['electric_stop_status' => $electric_stop_status]);
        }
        $res = SuccessCode::$statusOk;

        return json($res);
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     * 用户是否可以取消状态
     */
    public function cancelStatus()
    {
        $cancel_status = input('post.cancel_status', 0);
        $room_id = input('post.room_id');
        if ($room_id) {

            Db::name('room')->where(['room_id' => $room_id])->update(['cancel_status' => $cancel_status]);
        }
        $res = SuccessCode::$statusOk;

        return json($res);
    }

    public function copy()
    {
        $room_id = input('post.room_id');
        //查询房间信息
        $room = Db::name('room')->where(['room_id' => $room_id])->find();
        unset($room['room_id']);
        Db::name('room')->insert($room);
        $res = SuccessCode::$statusOk;

        return json($res);
    }
}

<?php


namespace app\server_api\controller\cabinet;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\data\Data;
use app\common\kg\Kg;
use app\common\user\User;
use app\server_api\controller\Base;
use think\facade\Db;

class Cabinet extends Base
{
    public $file_ld = [
        'cabinet_name',
        'device_sn',
        'room_id',
        'store_id',

    ];

    public function add()
    {

        $data = Data::clearEmpty($this->request->only($this->file_ld));
        $join_id = User::uid();

        $store = Db::name('cabinet')->where([
            'cabinet_name' => $data['cabinet_name'],
            'deleted_at' => 0,
            'join_id'=> $join_id
        ])->find();
        if ($store) {

            return json(ErrorCode::$code[6]);

        }
        $data['join_id'] = $join_id;
        //注册设备
        Kg::app()->device()->register($data['device_sn']);
        //写入数据
        Db::name('cabinet')->insertGetId($data);


        return json(SuccessCode::$statusOk);
    }
    public function edit()
    {

        $data = Data::clearEmpty($this->request->only($this->file_ld));
        $cabinet_id= input('post.cabinet_id');
        $where['cabinet_id'] = $cabinet_id;
        Db::name('cabinet')->where($where)->update($data);
        return json(SuccessCode::statusOkf());
    }
    public function list()
    {
        $join_id = User::uid();
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $public = input('post.public');
        $where = [
            'deleted_at' => 0,
            'join_id'=> $join_id
        ];

        $cabinet = \app\server_api\model\cabinet\Cabinet::where($where);
        $store_id = input('post.store_id]');
        if($store_id){
            $cabinet->where(['store_id]'=>$store_id]);
        }
        $room_id = input('post.room_id]');
        if($room_id){
            $cabinet->where(['room_id]'=>$room_id]);
        }


        $status = input('post.status');
        if(strlen($status)>0){
            $cabinet->where(['status'=>$status]);
        }
        if ($public) {
            $cabinet->where(function ($query) use ($public) {
                $query->where('cabinet_name', 'like', '%' . $public . '%');

            });
        }
        $admin_user_count = clone $cabinet;
        $count = $admin_user_count->count();
        $list = $cabinet->with(['room','store'])->page($page, $limit)->order(['cabinet_id'=>'desc'])->select()->toArray();

        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);

    }

    public function del()
    {
        $cabinet_id= input('post.cabinet_id');
        if($cabinet_id){

            Db::name('cabinet')->where(['cabinet_id'=>$cabinet_id])->update(['deleted_at'=>time()]);
        }
        return json(['status' => 1, 'msg' => '删除成功']);
    }
    public function status(){
        $cabinet_id= input('post.cabinet_id');
        $status= input('post.status');
        if($cabinet_id){

            Db::name('cabinet')->where(['cabinet_id'=>$cabinet_id])->update(['status'=>$status]);
        }
        return json(['status' => 1, 'msg' => '操作成功']);
    }

}

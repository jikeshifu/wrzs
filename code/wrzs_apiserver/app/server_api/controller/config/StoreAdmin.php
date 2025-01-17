<?php


namespace app\server_api\controller\config;


use app\common\code\SuccessCode;
use app\common\user\User;

use think\facade\Db;
use think\facade\Request;

class StoreAdmin
{
    public function list()
    {
        $join_id = User::uid();
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $where = [
            'deleted_at' => 0,
            'join_id' => $join_id
        ];
        $store_admin = Db::name('store_admin')->where($where);
        $store_admin_count = clone $store_admin;
        $count = $store_admin_count->count();
        $list = $store_admin->page($page, $limit)->order(['store_admin_id' => 'desc'])->select()->toArray();
        foreach ($list as &$vo){
            $vo['store_id_arr']=json_decode($vo['store_id_arr'],true);
        }
        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);
    }

    public function add()
    {
        $StoreAdmin = new \app\server_api\model\config\StoreAdmin();
        $data = Request::param(array_keys($StoreAdmin->schema));
        $join_id = User::uid();

        $data['join_id' ]= $join_id;

        if (!$StoreAdmin->addVerification($data)) {
            return json(['status' => 0, 'msg' => $StoreAdmin->error_msg]);
        }

        $StoreAdmin->json(['store_id_arr']);

        $StoreAdmin->save($StoreAdmin->Pdata);
        return json(SuccessCode::$statusOk);
    }


    public function edit()
    {
        $StoreAdmin = new \app\server_api\model\config\StoreAdmin();
        $data = Request::param(array_keys($StoreAdmin->schema));
        if (!$StoreAdmin->editVerification($data)) {
            return json(['status' => 0, 'msg' => $StoreAdmin->error_msg]);
        }
        $store_admin_id = input('post.store_admin_id');
        \app\server_api\model\config\StoreAdmin::where(['store_admin_id' => $store_admin_id])->save($StoreAdmin->Pdata);
        return json(SuccessCode::$statusOk);
    }

    public function resetPw()
    {
        $StoreAdmin = new \app\server_api\model\config\StoreAdmin();
        $data = Request::param(array_keys($StoreAdmin->schema));
        if (!$StoreAdmin->resetPw($data)) {
            return json(['status' => 0, 'msg' => $StoreAdmin->error_msg]);
        }
        $store_admin_id = input('post.store_admin_id');
        \app\server_api\model\config\StoreAdmin::where(['store_admin_id' => $store_admin_id])->save($StoreAdmin->Pdata);
        return json(SuccessCode::$statusOk);
    }

    public function del()
    {
        $store_admin_id = input('post.store_admin_id');
        \app\server_api\model\config\StoreAdmin::where(['store_admin_id' => $store_admin_id])->save([
            'deleted_at' => time()
        ]);
        return json(SuccessCode::$statusOk);
    }

    /**
     * 绑定门店
     */
    public function bindStore()
    {
        $store_admin_id = input('post.store_admin_id');
        $store_id_arr = input('post.store_id_arr');
        \app\server_api\model\config\StoreAdmin::where(['store_admin_id' => $store_admin_id])->json(['store_id_arr'])->save([
            'deleted_at' => time(),
            'store_id_arr'=>$store_id_arr
        ]);
        return json(SuccessCode::$statusOk);
    }

    /**
     * 编辑状态
     */
    public function editStatus()
    {
        $store_admin_id = input('post.store_admin_id');
        $status= input('post.status');
        \app\server_api\model\config\StoreAdmin::where(['store_admin_id' => $store_admin_id])->json(['store_id_arr'])->save([
            'status'=>$status
        ]);
        return json(SuccessCode::$statusOk);
    }


    /**
     * 编辑sms状态
     */
    public function editSmsStatus()
    {
        $store_admin_id = input('post.store_admin_id');
        $Status = input('post.sms_status');
        \app\server_api\model\config\StoreAdmin::where(['store_admin_id' => $store_admin_id])->json(['store_id_arr'])->save([
            'sms_status'=>$Status
        ]);
        return json(SuccessCode::$statusOk);
    }
}

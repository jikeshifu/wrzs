<?php


namespace app\admin_h5_api\controller\store;


use app\common\user\User;

class Store
{
    public function list()
    {
        $adminH5UserInfo = User::adminH5UserInfo();
        $store_ids = json_decode($adminH5UserInfo['store_id_arr'], true);
        $Store = \app\server_api\model\store\Store::where(['store_id' => $store_ids])->with(['roomListH5'])->select()->toArray();
        foreach ($Store as &$vo) {


            foreach ($vo['roomListH5'] as &$value) {
                unset($value['room_about']);
                $value['room_images'] = json_decode($value['room_images'], true);
            }
            $vo['roomList'] = $vo['roomListH5'];
            unset($vo['roomListH5']);
        }

        return json([
            'status' => 1,
            'list' => $Store,

        ]);

    }

    public function editStatus()
    {
        $status = input('post.status');
        $store_id = input('post.store_id');
        if($status!=1){
            $status=0;
        }
       $err =  \app\server_api\model\store\Store::editStatusVerification(['store_id'=>$store_id]);
        if(!$err){
            return json([
                'status' => 0,
                'msg' =>  \app\server_api\model\store\Store::$error_msg,
            ]);
        }
        \app\server_api\model\store\Store::where(['store_id'=>$store_id])->save(['status'=>$status]);

        return json([
            'status' => 1,
            'msg' =>'操作成功',
        ]);
    }
}

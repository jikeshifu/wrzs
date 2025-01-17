<?php


namespace app\admin_h5_api\controller\cabinet;


use app\common\code\SuccessCode;
use app\common\user\User;
use app\server_api\model\store\Store;

class Cabinet
{

    public function list()
    {

        $adminH5UserInfo = User::adminH5UserInfo();
        $store_ids = json_decode($adminH5UserInfo['store_id_arr'], true);

        $Cabinet = \app\model\cabinet\Cabinet::where(['store_id' => $store_ids])->with(['store','room'])->select()->toArray();
        $res =SuccessCode::$statusOk;
        $res['list'] =$Cabinet;
       return json($res);



    }
}

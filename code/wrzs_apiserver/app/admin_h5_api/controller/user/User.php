<?php


namespace app\admin_h5_api\controller\user;


use think\facade\Request;

class User
{
    public function login(){
        $StoreAdmin = new \app\server_api\model\config\StoreAdmin();
        $data = Request::param(array_keys($StoreAdmin->schema));
        if (!$StoreAdmin->loginVerification($data)) {
            return json(['status' => 0, 'msg' => $StoreAdmin->error_msg]);
        }

        return json([
            'status' => 1,
            'user_info' => $StoreAdmin->loginUserInfo['user_info'],
            'token' => $StoreAdmin->loginUserInfo['token'],
        ]);
    }
}

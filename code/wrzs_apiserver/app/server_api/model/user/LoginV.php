<?php


namespace app\server_api\model\user;


use app\common\code\ErrorCode;
use think\facade\Db;

class LoginV
{

    public $filed = [
        'account',
        'password'
    ];
    public $data;

    public $error = null;

    public $join_user =null;
    public function verification()
    {

        if (!$this->data['account'] || !$this->data['password']) {
            $this->error = ErrorCode::$adminCode[0];
            return;
        }
        $join_user = Db::name("join_user")->where([
            'account' => $this->data['account'],
            'deleted_at'=>0,
            'status'=>1
        ])->find();
        $this->join_user =$join_user;
        if (!$join_user) {
            $this->error = ErrorCode::$adminCode[1];
            return;
        }
        if (!password_verify($this->data['password'], $join_user['password'])) {

            $this->error = ErrorCode::$adminCode[1];
            return;
        }

    }
}

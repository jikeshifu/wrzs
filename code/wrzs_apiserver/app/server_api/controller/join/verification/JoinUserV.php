<?php


namespace app\admin_api\controller\join\verification;


use app\common\code\ErrorCode;
use think\facade\Db;

class JoinUserV
{
    static function pwhash($pw)
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($pw, PASSWORD_BCRYPT, $options);
    }

    static function edit($data)
    {
        $res['error'] = null;

        if (!$data['account'] ) {
            $res['error'] = ErrorCode::$adminCode[0];
            return $res;
        }

        $admin_user = Db::name("admin_user")->where([
            'account' => $data['account'],
            'deleted_at' => 0,
        ])->where('user_id','<>',$data['user_id'])->find();

        if ($admin_user) {
            $res['error'] = ErrorCode::$adminCode[2];
            return $res;

        }


        return $res;
    }
    static function add($data)
    {
        $res['error'] = null;

        if (!$data['account'] || !$data['password']) {
            $res['error'] = ErrorCode::$adminCode[0];
            return $res;
        }

        $admin_user = Db::name("admin_user")->where([
            'account' => $data['account'],
            'deleted_at' => 0
        ])->find();

        if ($admin_user) {
            $res['error'] = ErrorCode::$adminCode[2];
            return $res;

        }
        $res['password'] =self::pwhash($data['password']);

        return $res;
    }
}

<?php


namespace app\admin_api\controller\user\verification;


use app\common\code\ErrorCode;
use think\facade\Db;

class UserV
{
    static function login($account, $password)
    {
        $data['res'] = "";
        $data['error'] = "";
        if (!$account || !$password) {
            $data['error'] = ErrorCode::$adminCode[0];
            return $data;
        }

        $admin_user = Db::name("admin_user")->where([
            'account' => $account,
            'deleted_at' => 0
        ])->find();

        if (!$admin_user) {
            $data['error'] = ErrorCode::$adminCode[1];

            return $data;
        }
        if (!password_verify($password, $admin_user['password'])) {
            $data['error'] = ErrorCode::$adminCode[1];

            return $data;
        }
        $data['res']=$admin_user;
        return $data;

    }

    static function pwhash($pw)
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($pw, PASSWORD_BCRYPT, $options);
    }

    static function add($data)
    {
        if (!$account || !$password) {
            echo json_encode(ErrorCode::$adminCode[0]);
            die;
        }

        $admin_user = Db::name("admin_user")->where([
            'account' => $account,
            'deleted_at' => 0
        ])->find();

        if ($admin_user) {
            echo json_encode(ErrorCode::$adminCode[2]);
            die;
        }
        return self::pwhash($password);
    }
}

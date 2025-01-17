<?php


namespace app\module\admin\server;


use app\common\code\ErrorCode;
use app\module\server\Server;
use think\facade\Db;

class AdminUserServer extends Server
{
    static function login($account, $password)
    {
        $data['res'] = "";
        $data['error'] = "";
        if (!$account || !$password) {
           self::$error= ErrorCode::$adminCode[0];
            return ;
        }

        $admin_user = Db::name("admin_user")->where([
            'account' => $account,
            'deleted_at' => 0
        ])->find();

        if (!$admin_user) {
            self::$error=  ErrorCode::$adminCode[1];

            return ;
        }
        if (!password_verify($password, $admin_user['password'])) {
            self::$error=ErrorCode::$adminCode[1];

            return ;
        }

        return $admin_user;

    }

    static function pwhash($pw)
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($pw, PASSWORD_BCRYPT, $options);
    }

    static function add($account,$pwd){
        Db::name("admin_user")->insert([
            'account' => $account,
            'password' => self::pwhash($pwd),
            'deleted_at' => 0
        ]);
    }
}
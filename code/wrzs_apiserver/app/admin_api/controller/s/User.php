<?php


namespace app\admin_api\controller\s;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use think\facade\Db;

class User
{

    public function pwd()
    {
        $old_password = input("old_password");
        $password = input("password");
        $user_id = \app\common\user\User::uid();
  
        $admin_user = Db::name("admin_user")->where([
            'user_id' => $user_id,

        ])->find();

        if (!password_verify($old_password, $admin_user['password'])) {

            return json(ErrorCode::errorF());

        }

        Db::name("admin_user")->where([
            'user_id' => $user_id,

        ])->update(["password"=>self::pwhash($password)]);
        return json(SuccessCode::statusOkf());


    }

    static function pwhash($pw)
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($pw, PASSWORD_BCRYPT, $options);
    }
}

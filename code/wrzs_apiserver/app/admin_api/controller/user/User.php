<?php


namespace app\admin_api\controller\user;


use app\admin_api\controller\user\verification\UserV;
use app\common\auth\Jwt;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\user\Token;
use think\facade\Db;

class User
{
    public function login()
    {

        $account = input('account');
        $password = input('password');
        //验证
        $UserVRes = UserV::login($account, $password);

        if($UserVRes['error']){
            return json($UserVRes['error']);
        }
        $admin_user =$UserVRes['res'];
        $res =SuccessCode::$statusOk;

        $res['token']=     Jwt::GetToken($admin_user['user_id']);
        $res['userInfo']=$admin_user;
        return json($res);
    }

    public function add()
    {
        $account = input('account');
        $password = input('password');
        //验证
        $pw = UserV::add($account, $password);
        Db::name("admin_user")->insertGetId([
            'account' => $account,
            'password' => $pw,
            'created_at' => time(),
            'pid' => 0
        ]);
        return json(SuccessCode::$code[0]);
    }
}

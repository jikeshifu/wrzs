<?php


namespace app\server_api\controller\user;


use app\common\auth\Jwt;
use app\common\code\SuccessCode;
use app\common\user\Token;
use app\middleware\JwtAuth;
use app\server_api\model\user\LoginV;
use think\facade\Request;

class Login
{
    /**
     * 账号密码登录
     */
    public function account()
    {
        $LoginV = new LoginV();
        $data = Request::only($LoginV->filed);
        $LoginV->data = $data;
        $LoginV->verification();
        if ($LoginV->error) {
            return json($LoginV->error);
        }
        $res = SuccessCode::$statusOk;
        $res['user_info'] = $LoginV->join_user;

        $res['token']=Jwt::GetToken( $LoginV->join_user['user_id']);
        return json($res);
    }

}

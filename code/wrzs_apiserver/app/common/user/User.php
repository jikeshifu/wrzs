<?php


namespace app\common\user;



use app\common\auth\Jwt;
use app\common\cache\Redis;
use think\facade\Db;
use think\facade\Request;

class User
{

    static function uid()
    {


        if (Request::header('Authorization')) {
               $data=  Jwt::GetUid(Request::header('Authorization'));
            return $data["member_id"];
        }
        return 0;
    }
    static function adminInfo(){

          return  Db::name('admin_user')->where(['user_id'=>self::uid()])->find();
    }
    static function userInfo(){

        return  Db::name('member_wechat')->where(['member_id'=>self::uid()])->find();
    }

    static function adminH5UserInfo(){

        return  Db::name('store_admin')->where(['store_admin_id'=>self::uid()])->find();
    }
    static function wxappid(){


        return  self::uid();
    }
}

<?php


namespace app\common\log;


use app\common\user\User;
use think\facade\Db;
use think\facade\Request;

class AdminLog
{
    static function operationLog($title,$uid="")
    {
       $ip= Request::ip();
        if(!$uid){
            $uid =User::uid();
        }

       $admin_user= Db::name('admin_user')->where(['user_id'=>$uid])->find();
        Db::name('admin_log')->insert([
            'title' => $title,
            'created_at' => time(),
            'user_id' =>$uid,
            'username'=>$admin_user['username'],
            'ip'=>$ip
        ]);
    }
}

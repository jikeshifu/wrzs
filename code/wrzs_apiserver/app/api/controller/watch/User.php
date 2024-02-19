<?php


namespace app\api\controller\watch;


use app\common\auth\Jwt;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\data\Data;
use app\common\kg\Kg;
use app\common\user\Token;
use think\facade\Db;

class User
{


    public function mobileCode()
    {
        $code = input('post.code');
        $iv = input('post.iv');
        $encryptedData = input('post.encryptedData');


        $miniProgram = Kg::app()->watch()->miniProgram();
        $user = $miniProgram->auth->session($code);

        $decryptedData = $miniProgram->encryptor->decryptData($user['session_key'], $iv, $encryptedData);

        Db::name('member_wechat')
            ->where(['member_id' => \app\common\user\User::uid()])
            ->update(['mobile' => $decryptedData['phoneNumber']]);
        $res = SuccessCode::$code[0];
        $res['mobile'] = $decryptedData['phoneNumber'];
        return json($res);
    }

    public function login()
    {
        //假如携带有token

        $code = input('post.code');


        if (!$code) {
            return json(['error_code' => 1, 'msg' => 'code不能为空',]);
        }

        $miniProgram = Kg::app()->watch()->miniProgram();
        $user = $miniProgram->auth->session($code);


        if (array_key_exists('errcode', $user)) {
            return json(['error_code' => 1, 'msg' => $user['errmsg'],]);
        }
        $member_wechat = Db::name('member_wechat')->where(['openid' => $user['openid']])->find();
        if (!$member_wechat) {
            $member_id = Db::name('member_wechat')->insertGetId(['openid' => $user['openid']]);
            Db::name('member_wallet')->insertGetId(['member_id' => $member_id]);
            $member_wechat = [
                'avatar_url' => null,
                'created_at' => null,
                'gender' => null,
                'member_id' => (int)$member_id,
                'nick_name' => null,
                'openid' => $user['openid'],
                'province' => null,

            ];


            //将新用户丢进管道
            $Beanstalkd = Kg::app()->Beanstalkd();


            $Beanstalkd->useTube('rrt-userIni')->put(json_encode([
                'member_id' => $member_id,

            ]));

        }

        return json([
            'error_code' => 0,
            'token' => Jwt::GetToken($member_wechat['member_id']),
            'userInfo' => $member_wechat,
            'session_key' => $user['session_key'],

        ]);
    }

    function edit()
    {
        $data['nick_name'] = input('post.nick_name');

        $data['avatar_url'] = input('post.avatar_url');
        //        $data['province'] = input('post.province');
//        $data['gender'] = input('post.gender');
//        $data['mobile'] = input('post.mobile');
        try {
            Db::name('member_wechat')->where(['member_id' => \app\common\user\User::uid()])->update(Data::clearEmpty($data));
        } catch (\Exception $e) {
            $error = ErrorCode::$code[0];
            $error['msg'] = $e->getMessage();
            return json($error);
        }
        return json(SuccessCode::$code[0]);
    }
}

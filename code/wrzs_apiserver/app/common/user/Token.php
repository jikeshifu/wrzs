<?php


namespace app\common\user;



use app\common\cache\Redis;

class Token
{

    static function setToken($id, $expire = (3600*12))
    {
        $data[] = base64_encode($id);
        $data[] = base64_encode(time() + $expire);
        $data[] = base64_encode(md5(rand(100000, 999999)) . md5(rand(100000, 999999)) . md5(rand(100000, 999999)));
        $redis = Redis::redis();
        $redis->hSet('user:' . $id,'token', $data[2]);

        return implode('.', $data);
    }

    static function getToken($data)
    {
        $data = explode('.', $data);
        $data = explode('.', base64_decode($data[0]));
        return $data[1];
    }

    static function validate($token)
    {

        $data = explode('.', $token);

//        if (base64_decode($data[1]) < time()) {
//            return false;
//        }

        $redis = Redis::redis();
        if ($redis->hGet('user:' . base64_decode($data[0]),'token') != $data[2]) {
            return false;
        }
        return true;
    }
}

<?php


namespace app\common\auth;


class Jwt
{
    //header头部

    private static $header = [

        'alg' => 'HS256',  // 声明加密的算法 通常直接使用 HMAC SHA256

        'typ' => 'JWT',    // 声明类型

    ];

    //secret密钥

    private static $secret = "abcdefgh@hh12g38kjkj";

    /**
     * 生成签名
     * @param array $payload 载荷
     * @return array      返回结果
     */

    public static function GetToken($member_id)

    {


        $payload = array('member_id'=>$member_id, 'iat'=>time(), 'exp'=>time()+(3600*24));
        if (!is_array($payload)) {

            return null;

        }

        //头部

        $base_header = base64_encode(json_encode(self::$header));

        //载荷

        $base_payload = base64_encode(json_encode($payload));

        //签证：header连接payload

        $sign = self::signature($base_header . $base_payload, self::$secret);

        //拼接成token

        $token = $base_header . '.' . $base_payload . '.' . $sign;

        return $token;

    }
    public static function GetUid($token){
        //字符串转数组

        $tokens = explode('.', $token);

        if (count($tokens) != 3) {

            return ['status' => 0, 'msg' => '字节数不对'];

        }

        //变量赋值

        list($base_header, $base_payload, $base_sign) = $tokens;

        //header头部

        $base_header_verify = (array)json_decode(base64_decode($base_header));
        if (empty($base_header_verify['alg'])) {

            return ['status' => 0, 'msg' => 'alg不存在'];

        }

        //验证payload

        $base_payload_verify = (array)json_decode(base64_decode($base_payload));

        return $base_payload_verify;
    }

    /**
     * 验证签名
     * @param $token  签名token
     * @return array  返回结果
     */

    public static function verifyToken($token)

    {

        //字符串转数组

        $tokens = explode('.', $token);

        if (count($tokens) != 3) {

            return "字节数不对";

        }

        //变量赋值

        list($base_header, $base_payload, $base_sign) = $tokens;

        //header头部

        $base_header_verify = (array)json_decode(base64_decode($base_header));

        if (empty($base_header_verify['alg'])) {
            return "alg不存在";


        }

        //验证payload

        $base_payload_verify = (array)json_decode(base64_decode($base_payload));

        if (!isset($base_payload_verify['iat']) || $base_payload_verify['iat'] > time()) {

            return ['status' => 0, 'msg' => '签发时间大于当前服务器时间验证失败'];

        }

        if (!isset($base_payload_verify['exp']) || $base_payload_verify['exp'] < time()) {
            return "签名过期";


        }

        //验证签证

        $sign = self::signature($base_header . $base_payload, self::$secret);

        if ($base_sign != $sign) {
            return "验证失败";


        }

        return false;

    }

    /**
     * 签证
     * @param $input    header和payload
     * @param $secret   密钥
     * @param string $alg 加密方式
     * @return string   返回签证字符串
     */

    public static function signature($input, $secret, $alg = 'HS256')

    {

        $sign_md5 = $input . $secret . $alg;

        return md5($sign_md5);

    }

}


<?php

namespace app\common\code;
class Code
{
    static function Ok($data = [])
    {
        $data['error_code'] = 0;
        if (!isset($data['msg'])) {
            $data['msg'] = "操作成功";
        }
        return $data;
    }

    static function Err($data = [])
    {

        if (!isset($data['msg'])) {
            $data['msg'] = "操作失败";
        }
        if (!isset($data['error_code'])) {
            $data['error_code'] = 1000;
        }
        return $data;
    }
}
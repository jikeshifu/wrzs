<?php


namespace app\common\code;


class SuccessCode
{
    static function statusOkf($data=[]){
        $data['error_code']=0;
        $data['status']=1;
        if(!isset($data['msg']) || !$data['msg']){
            $data['msg']="操作成功";
        }
        return $data;
    }
    static $code = [
        "0" => ['error_code' => 0, "msg" => "操作成功"]
    ];

    static $statusOk=['status'=>1,'error_code' => 0, "msg" => "操作成功"];
}

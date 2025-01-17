<?php


namespace app\api\controller\ad;


use app\common\code\SuccessCode;
use think\facade\Db;

class Ad
{
    function list()
    {

        return json(SuccessCode::statusOkf([
            "list" => Db::name('ad')->where(["deleted_at"=>0])->select()->toArray(),
        ]));
    }
}
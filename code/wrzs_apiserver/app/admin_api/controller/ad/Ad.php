<?php

namespace app\admin_api\controller\ad;

use app\common\code\SuccessCode;
use think\facade\Db;
use think\facade\Request;

class Ad
{
    public $filed = [
        "ad_id",
        "ad_name",
        "content",
        "src",
        "status",
    ];
    public function getData()
    {
        return json(SuccessCode::statusOkf([
            "list" => Db::name('ad')->where(["deleted_at" => 0])->select()->toArray(),
        ]));
    }
    public function postData()
    {
        $data = Request::only($this->filed);
        $data["created_at"] = time();
        Db::name('ad')->where(["ad_id" => input("ad_id")])->insert($data);
        return json(SuccessCode::statusOkf());
    }
    public function putById(int $id = null)
    {
        $data = Request::only($this->filed);
        $data["updated_at"] = time();
        Db::name('ad')->where(["ad_id" => input("ad_id")])->update($data);
        return json(SuccessCode::statusOkf());
    }
    public function deleteById(int $id = null)
    {
        $data = Request::only($this->filed);
        $data["deleted_at"] = time();
        Db::name('ad')->where(["ad_id" => input("ad_id")])->update($data);
        return json(SuccessCode::statusOkf());
    }
}

<?php


namespace app\api\controller\cabinet;


use app\common\code\SuccessCode;
use think\facade\Db;

class Goods
{
    public function info()
    {
        $goods_id = input('goods_id');
        $cabinet_goods = Db::name("cabinet_goods")->where(["goods_id" => $goods_id])->find();
        $res =SuccessCode::$statusOk;
        $res['info']=$cabinet_goods;
        return json($res);
    }
}

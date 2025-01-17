<?php


namespace app\api\controller\shop;


use app\common\code\SuccessCode;
use app\common\user\User;
use think\facade\Request;

class ShoppingCar
{

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $where = [
            'member_id'=>User::uid(),
            'deleted_at' => 0
        ];

        $model = \app\model\shop\ShoppingCar::where($where)->with(['goodsInfo']);

        $modelCount = clone $model;
        $count = $modelCount->count();
        $list = $model->page($page, $limit)->select()->toArray();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }
    public function add()
    {
        $Goods = new \app\model\shop\ShoppingCar();
        $data = Request::only($Goods->field);
        $Goods->add($data);
        if ($Goods->error) {
            return json($Goods->error);
        }
        return json(SuccessCode::$statusOk);
    }
    public function edit()
    {
        $Goods = new \app\model\shop\ShoppingCar();
        $data = Request::only($Goods->field);
        $Goods->edit($data);
        if ($Goods->error) {
            return json($Goods->error);
        }
        return json(SuccessCode::$statusOk);
    }
    public function del(){
        $Goods = new \app\model\shop\ShoppingCar();
        $data = Request::only($Goods->field);
        $Goods->del($data);
        if ($Goods->error) {
            return json($Goods->error);
        }
        return json(SuccessCode::$statusOk);
    }

    public function dels(){
        $Goods = new \app\model\shop\ShoppingCar();
        $data = Request::only($Goods->field);
        $Goods->dels($data);
        if ($Goods->error) {
            return json($Goods->error);
        }
        return json(SuccessCode::$statusOk);
    }
}

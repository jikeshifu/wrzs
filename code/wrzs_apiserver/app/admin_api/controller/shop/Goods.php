<?php


namespace app\admin_api\controller\shop;


use app\common\code\SuccessCode;

use think\facade\Db;
use think\facade\Request;

class Goods
{

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $where = [

            'deleted_at' => 0
        ];
        $type_id = input('post.type_id');
        if ($type_id) {
            $where['type_id'] = $type_id;
        }


        $Goods = \app\model\shop\Goods::where($where)->with(['goodsType']);
        $goods_name = input('post.goods_name');
        if ($goods_name) {
            $Goods->where('goods_name','like','%'.$goods_name.'%');

        }
        $GoodsCount = clone $Goods;
        $count = $GoodsCount->count();
        $list = $Goods->page($page, $limit)->order(['sort' => 'desc', 'goods_id' => 'desc'])->select()->toArray();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function add()
    {
        $Goods = new \app\model\shop\Goods();
        $data = Request::only($Goods->field);
        $Goods->add($data);
        if ($Goods->error) {
            return json($Goods->error);
        }


        return json(SuccessCode::$statusOk);
    }

    public function edit()
    {
        $Goods = new \app\model\shop\Goods();
        $data = Request::only($Goods->field);
        $Goods->edit($data);
        if ($Goods->error) {
            return json($Goods->error);
        }
        return json(SuccessCode::$statusOk);
    }

    public function del()
    {
        $Goods = new \app\model\shop\Goods();
        $data = Request::only($Goods->field);
        $Goods->del($data);
        if ($Goods->error) {
            return json($Goods->error);
        }
        return json(SuccessCode::$statusOk);
    }
}

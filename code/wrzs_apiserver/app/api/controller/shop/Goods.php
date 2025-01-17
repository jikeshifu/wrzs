<?php


namespace app\api\controller\shop;


use app\common\code\SuccessCode;
use think\facade\Db;

class Goods
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $where = [

            'deleted_at' => 0,
            'status' => 1
        ];
        $type_id = input('post.type_id');
        if ($type_id) {
            $where['type_id'] = $type_id;
        }
        $Goods = \app\model\shop\Goods::where($where);
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

    public function goodsType(){
        $list=Db::name("goods_type")->where(['deleted_at'=>0,"status"=>1])->select()->toArray();

        $res = SuccessCode::$statusOk;
        $res['list'] = $list;

        return json($res);
    }

    public function recommend(){
        $list=Db::name("goods")->where([
            'deleted_at'=>0,
            'status'=>1,
            'recommend_status'=>1
        ])->select()->toArray();

        $res = SuccessCode::$statusOk;
        $res['list'] = $list;

        return json($res);

    }
}

<?php


namespace app\admin_api\controller\shop;


use app\common\code\SuccessCode;

use think\facade\Db;
use think\facade\Request;

class GoodsType
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


        $model = \app\model\shop\GoodsType::where($where);
        $type_name = input('post.type_name');
        if ($type_name) {
            $model->where('type_name','like','%'.$type_name.'%');

        }
        $modelCount = clone $model;
        $count = $modelCount->count();
        $list = $model->page($page, $limit)->order(['sort' => 'desc', 'type_id' => 'desc'])->select()->toArray();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function add()
    {
        $model = new \app\model\shop\GoodsType();
        $data = Request::only($model->field);
        $model->add($data);
        if ($model->error) {
            return json($model->error);
        }
        return json(SuccessCode::$statusOk);
    }

    public function edit()
    {
        $model = new \app\model\shop\GoodsType();
        $data = Request::only($model->field);
        $model->edit($data);
        if ($model->error) {
            return json($model->error);
        }
        return json(SuccessCode::$statusOk);
    }

    public function del()
    {
        $model = new \app\model\shop\GoodsType();
        $data = Request::only($model->field);
        $model->del($data);
        if ($model->error) {
            return json($model->error);
        }
        return json(SuccessCode::$statusOk);
    }
}

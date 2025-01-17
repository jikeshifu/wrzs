<?php


namespace app\admin_api\controller\region;


use app\common\code\SuccessCode;
use think\facade\Request;

class City
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $where = [

            'deleted_at' => 0
        ];


        $model = \app\model\region\City::where($where);
        $city_name = input('post.city_name');
        if ($city_name) {
            $model->where('city_name','like','%'.$city_name.'%');

        }
        $modelCount = clone $model;
        $count = $modelCount->count();
        $list = $model->page($page, $limit)->order(['sort' => 'desc', 'city_id' => 'desc'])->select()->toArray();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function add()
    {
        $model = new \app\model\region\City();
        $data = Request::only($model->field);
        $model->add($data);
        if ($model->error) {
            return json($model->error);
        }
        return json(SuccessCode::$statusOk);
    }

    public function edit()
    {
        $model = new \app\model\region\City();
        $data = Request::only($model->field);
        $model->edit($data);
        if ($model->error) {
            return json($model->error);
        }
        return json(SuccessCode::$statusOk);
    }

    public function del()
    {
        $model = new \app\model\region\City();
        $data = Request::only($model->field);
        $model->del($data);
        if ($model->error) {
            return json($model->error);
        }
        return json(SuccessCode::$statusOk);
    }
}

<?php


namespace app\server_api\controller\cabinet;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\data\Data;
use app\common\kg\Kg;
use app\common\user\User;
use app\server_api\controller\Base;
use think\facade\Db;

class Goods extends Base
{
    public $file_ld = [
        'goods_name',
        'goods_price',
        'goods_image',
        'goods_about',
        'cabinet_id',
        'cabinet_number',
        'label'


    ];

    public function add()
    {

        $data = Data::clearEmpty($this->request->only($this->file_ld));


        $store = Db::name('cabinet_goods')->where([
            'cabinet_number' => $data['cabinet_number'],
            'deleted_at' => 0,
            'cabinet_id'=> $data['cabinet_id'],
        ])->find();
        if ($store) {

            return json(ErrorCode::$adminCode[5]);
        }


        Db::name('cabinet_goods')->insertGetId($data);

        return json(SuccessCode::$statusOk);
    }
    public function edit()
    {

        $data = Data::clearEmpty($this->request->only($this->file_ld));
        $goods_id= input('post.goods_id');
        $where['goods_id'] = $goods_id;
        Db::name('cabinet_goods')->where($where)->update($data);
        return json(SuccessCode::$statusOk);
    }
    public function list()
    {

        $cabinet_id   =input('post.cabinet_id');
        if(!$cabinet_id){
            return json(ErrorCode::$adminCode[6]);
        }
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $public = input('post.public');

        $where = [
            'deleted_at' => 0,
            'cabinet_id'=>input('post.cabinet_id')
        ];

        $cabinet_goods = Db::name('cabinet_goods')->where($where);


        $status = input('post.status');
        if(strlen($status)>0){
            $cabinet_goods->where(['status'=>$status]);
        }
        if ($public) {
            $cabinet_goods->where(function ($query) use ($public) {
                $query->where('goods_name', 'like', '%' . $public . '%');

            });
        }
        $admin_user_count = clone $cabinet_goods;
        $count = $admin_user_count->count();
        $list = $cabinet_goods->page($page, $limit)->order(['cabinet_number'=>'aes'])->select()->toArray();
      $cabinet =  Db::name('cabinet')->where($where)->find();
      $data=  Kg::app()->device()->cabinet($cabinet['device_sn'])->state();
        foreach ($list as &$vo){
            $vo['lock_status']=$data['data'][$vo['cabinet_number']];

        }

        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);
    }

    public function del()
    {
        $goods_id= input('post.goods_id');
        if($goods_id){

            Db::name('cabinet_goods')->where(['goods_id'=>$goods_id])->update(['deleted_at'=>time()]);
        }
        return json(SuccessCode::$statusOk);
    }
    public function status(){
        $goods_id= input('post.goods_id');
        $status= input('post.status');
        if($goods_id){

            Db::name('cabinet_goods')->where(['goods_id'=>$goods_id])->update(['status'=>$status]);
        }
        return json(SuccessCode::$statusOk);
    }

}

<?php


namespace app\server_api\controller\discount;


use app\common\user\User;
use app\server_api\model\discount\DiscountReduce;
use think\facade\Db;
use think\facade\Request;

class Reduce
{

    public $file_ld = [
        'title',
        'full',
        'reduce',
        'room_id'
    ];
    public function add()
    {
        $data = Request::only($this->file_ld);
        $wxapp_id = User::wxappid();
//        $data['wxapp_id'] = $wxapp_id;
        $data['created_at'] = time();
        Db::name("discount_reduce")->insert($data);
        return json(['status' => 1, 'msg' => '添加成功']);

    }



    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $title = input('post.title');
        $room_id = input('post.room_id' );
        if(!$room_id){
            return json(['status' => 1, 'msg' => 'room_id不能为空']);
        }
        $wxapp_id = User::wxappid();
        $where = [
            'deleted_at' => 0,
//            'wxapp_id' => $wxapp_id,
            'room_id' => $room_id,
        ];

        $discount_coupons =   DiscountReduce::where($where);

        if ($title) {
            $discount_coupons->where('title', 'like', '%' . $title . '%');;
        }
        $discount_coupons_count = clone $discount_coupons;
        $count = $discount_coupons_count->count();
        $list = $discount_coupons->with('room')->page($page, $limit)->order(['reduce_id' => 'desc'])->select()->toArray();

        return json(['status' => 1, 'msg' => '操作成功', 'list' => $list, 'count' => $count]);
    }

    public function edit()
    {
        $data = Request::only($this->file_ld);

        $reduce_id= input('post.reduce_id');
        if (!$reduce_id) {
            return json(['status' => 0, 'msg' => 'reduce_id不能为空']);
        }
        $data['updated_at'] = time();

        Db::name("discount_reduce")->where(['reduce_id' => $reduce_id])->update($data);
        return json(['status' => 1, 'msg' => '更新成功']);
    }

    public function del()
    {


        $reduce_id= input('post.reduce_id');
        if (!$reduce_id) {
            return json(['status' => 0, 'msg' => 'reduce_id不能为空']);
        }

        $data['deleted_at'] = time();
        Db::name("discount_reduce")->where(['reduce_id' => $reduce_id])->update($data);
        return json(['status' => 1, 'msg' => '删除成功']);
    }
}

<?php


namespace app\admin_api\controller\discount;


use app\common\cache\Redis;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\user\User;
use think\facade\Db;
use think\facade\Request;

class Coupons
{
    public $file_ld = [
        'title',
        'money',
        'new_user_status',
        'day_number'
    ];

    public function add()
    {

        $data = Request::only($this->file_ld);

        $data['created_at'] = time();
        Db::name("discount_coupons")->insert($data);
        return json(SuccessCode::$statusOk);
    }

    public function edit()
    {
        $data = Request::only($this->file_ld);

        $coupons_id = input('post.coupons_id');
        if (!$coupons_id) {
            return json(['status' => 0, 'msg' => 'coupons_id不能为空']);
        }
        $data['updated_at'] = time();

        Db::name("discount_coupons")->where(['coupons_id' => $coupons_id])->update($data);
        return json(SuccessCode::$statusOk);
    }

    public function del()
    {


        $coupons_id = input('post.coupons_id');
        if (!$coupons_id) {
            return json(['status' => 0, 'msg' => 'coupons_id不能为空']);
        }

        $data['deleted_at'] = time();
        Db::name("discount_coupons")->where(['coupons_id' => $coupons_id])->update($data);
        return json(SuccessCode::$statusOk);
    }

    public function give()
    {
        $coupons_id = input('post.coupons_id');
        if (!$coupons_id) {
            return json(ErrorCode::$adminCode[6]);
        }
        $mobile = input('post.mobile');
        if (!$mobile) {
            return json(ErrorCode::$adminCode[16]);
        }
        //查询优惠券信息
        $discount_coupons = Db::name("discount_coupons")->field('money,title,coupons_id,day_number')->where(['coupons_id' => $coupons_id])->find();
        $discount_coupons['created_at'] = time();
        //查询用户信息
        $member_wechat = Db::name("member_wechat")->where([
            'mobile' => $mobile,

        ])->find();

        if (!$member_wechat) {
            return json(ErrorCode::$adminCode[17]);
        }
        $discount_coupons['member_id'] = $member_wechat['member_id'];

        $discount_coupons['end_time'] = time() + ($discount_coupons['day_number'] * 86400);
        unset($discount_coupons['day_number']);
        Db::name("member_coupons")->insert($discount_coupons);
        return json(SuccessCode::$statusOk);
    }

    public function gives()
    {
        $coupons_id = input('coupons_id');
        if (!$coupons_id) {
            return json(ErrorCode::$adminCode[6]);
        }


        //查询优惠券信息
        $discount_coupons = Db::name("discount_coupons")->field('money,title,coupons_id,day_number')->where(['coupons_id' => $coupons_id])->find();
        $discount_coupons['created_at'] = time();


        $discount_coupons['end_time'] = time() + ($discount_coupons['day_number'] * 86400);

       $member = Db::name("member")->field('member_id')->select()->toArray();
        $redis = Redis::redis();
       foreach ($member as $vo){
           $discount_coupons['member_id'] =$vo['member_id'];

           $redis->lPush('rrt-givesCoupons',json_encode($discount_coupons));
       }

//        unset($discount_coupons['day_number']);
//        Db::name("member_coupons")->insert($discount_coupons);
        return json(SuccessCode::$statusOk);
    }

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $title = input('post.title');

        $where = [
            'deleted_at' => 0,

        ];
        $discount_coupons = Db::name('discount_coupons')->where($where);

        if ($title) {
            $discount_coupons->where('title', 'like', '%' . $title . '%');;
        }
        $discount_coupons_count = clone $discount_coupons;
        $count = $discount_coupons_count->count();
        $list = $discount_coupons->page($page, $limit)->order(['coupons_id' => 'desc'])->select()->toArray();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }
}

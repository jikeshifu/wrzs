<?php


namespace app\api\controller\user;


use app\common\code\SuccessCode;
use app\common\user\User;
use think\facade\Db;

class Coupons
{

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $member_id = User::uid();
        $where = [
            'use_time' => 0,
            'member_id' => $member_id
        ];
        $discount_coupons = Db::name('member_coupons')->where($where)->where("end_time",">",time());


        $discount_coupons_count = clone $discount_coupons;
        $count = $discount_coupons_count->count();
        $list = $discount_coupons->page($page, $limit)->order(['coupons_id' => 'desc'])->select()->toArray();

        $res =SuccessCode::$code[0];
        $res['list'] =$list;
        $res['count'] =$count;
        return json($res);
    }
}

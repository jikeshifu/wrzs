<?php


namespace app\api\controller\user;


use app\common\code\SuccessCode;
use app\common\user\User;
use think\facade\Db;

class Consume
{
    public function record()
    {

        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
     

        $member_id = User::uid();
        $member_pay_record = Db::name('member_pay_record')->where(['member_id' => $member_id]);
        $count = clone $member_pay_record;
        $type = input('post.type');
        if($type){
            $member_pay_record->where(['type'=>$type]);
        }

            $member_pay_record->where('created_at','>',input('post.yc'));


            $member_pay_record->where('created_at','<',input('post.yw')+86359);


        $list = $member_pay_record->page($page, $limit)->order(['record_id'=>'desc'])->select()->toArray();
        $res =SuccessCode::$code[0];
        $res['list']=$list;
        $res['sql']=Db::name('member_pay_record')->getLastSql();
        $res['count']=$count->count();
        return json($res);


    }
}

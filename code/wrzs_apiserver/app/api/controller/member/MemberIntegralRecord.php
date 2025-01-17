<?php


namespace app\api\controller\member;


use app\common\code\SuccessCode;
use app\common\user\User;

class MemberIntegralRecord
{
    public function list()
    {
        $member_id = User::uid();
        $page =input("page");
        $limit =input("limit");
        $model =\app\module\member\model\MemberIntegralRecord::where(['member_id' => $member_id]);
        return json(SuccessCode::statusOkf([
            'list'=>$model->page($page,$limit)->order("record_id desc")->select()->toArray(),
            "count"=>$model->count()
        ]));
    }
}
<?php


namespace app\server_api\controller\join;


use app\common\code\SuccessCode;
use think\facade\Db;

class Wthdrawal
{
    public function list()
    {




        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $join_id =\app\common\user\User::uid();
        $list =Db::name("join_withdrawal")->where([
            "join_id" => $join_id,
        ])->page($page, $limit)->order(['withdrawal_id' => 'desc'])->select();

        $count=    Db::name("join_withdrawal")->where([
            "join_id" =>      $join_id =\app\common\user\User::uid(),
        ])->count();
        return json(  SuccessCode::statusOkf([ 'msg' => '操作成功', 'list' => $list, 'count' => $count]));
    }
}

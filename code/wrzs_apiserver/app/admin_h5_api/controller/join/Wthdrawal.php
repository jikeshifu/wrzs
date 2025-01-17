<?php


namespace app\admin_h5_api\controller\join;


use app\common\code\SuccessCode;
use think\facade\Db;

class Wthdrawal
{
    public function list()
    {
        $join_wallet = \app\server\join\Wallet::join_wallet();



        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $list =Db::name("join_withdrawal")->where([
            "join_id" => $join_wallet['join_id'],
        ])->page($page, $limit)->order(['withdrawal_id' => 'desc'])->select();

        $count=    Db::name("join_withdrawal")->where([
            "join_id" => $join_wallet['join_id'],
        ])->count();

        return json(     SuccessCode::statusOkf([ 'msg' => '操作成功', 'list' => $list, 'count' => $count]));
    }
}

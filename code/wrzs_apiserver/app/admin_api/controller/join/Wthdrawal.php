<?php


namespace app\admin_api\controller\join;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\model\join\JoinWithdrawal;
use app\server\join\Wallet;
use think\facade\Db;
use think\facade\Request;

class Wthdrawal
{
    public function list()
    {


        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $model = new JoinWithdrawal();
        $join_id= input('post.user_id');
        if($join_id){
            $model->where(['join_id'=>$join_id]);
        }
        $list=$model->page($page, $limit)->with("joinUser")->order(['withdrawal_id' => 'desc', 'status' => "asc"])->select();
        $count = $model->count();
        return json(     SuccessCode::statusOkf([ 'msg' => '操作成功', 'list' => $list, 'count' => $count]));
    }

    public function examine()
    {
        $withdrawal_id = input("withdrawal_id");
        $join_withdrawal = Db::name("join_withdrawal")->where(["withdrawal_id" => $withdrawal_id])->find();
        if ($join_withdrawal["status"] == 1) {
            return json(SuccessCode::statusOkf(["msg" => "操作成功"]));
        }
        Db::startTrans();
        try {
            $type = input("type");
            Db::name("join_withdrawal")->where(["withdrawal_id" => $withdrawal_id])->update([
                "status" => $type,
                "remarks" => input("remarks")
            ]);
            if ($type == 1) {
                Wallet::reduce($join_withdrawal);
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(ErrorCode::errorF($e->getMessage()));
        }
        return json(SuccessCode::statusOkf(["msg" => "操作成功"]));
    }
}

<?php


namespace app\admin_h5_api\controller\join;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\user\User;

use think\facade\Db;

class Wallet
{
    public function info()
    {
        $join_wallet = \app\server\join\Wallet::join_wallet();
        return json(SuccessCode::statusOkf(['info' => $join_wallet]));

    }

    public function wthdrawal()
    {
        $join_wallet = \app\server\join\Wallet::join_wallet();
        $money = input("money");

        if ($money <2000) {
            return json(ErrorCode::errorF(["msg" => "单次提现不能小于2000"]));
        }
        //预留1000
        if ($money > ($join_wallet["money"]-1000)) {
            return json(ErrorCode::errorF(["msg" => "余额不足"]));
        }
        $join_withdrawal = Db::name("join_withdrawal")->where([
            "join_id" => $join_wallet['join_id'],
            "status" => 0
        ])->find();
        if ($join_withdrawal) {
            return json(ErrorCode::errorF(["msg" => "当前有未审核的提现申请失败"]));
        }
        \app\server\join\Wthdrawal::add($money, $join_wallet['join_id']);
        return json(SuccessCode::statusOkf(['msg' => "申请成功"]));
    }
}

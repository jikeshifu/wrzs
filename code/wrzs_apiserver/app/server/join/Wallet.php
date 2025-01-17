<?php


namespace app\server\join;


use app\common\user\User;
use think\facade\Db;

class Wallet
{
    static function add($join_id)
    {

        Db::name("join_wallet")->insert([
            "join_id" => $join_id,
            "created_at"=>time(),
        ]);
    }
    static function del($join_id)
    {

        Db::name("join_wallet")->where(["join_id" => $join_id])->update([
            "deleted_at"=>time(),
        ]);
    }

    static function join_wallet(){
        $UserInfo = User::adminH5UserInfo();

        return Db::name("join_wallet")->where(['join_id'=>$UserInfo["join_id"]])->find();
    }
    static function reduce($join_withdrawal){
       $join_wallet = Db::name("join_wallet")->where(["join_id"=>$join_withdrawal["join_id"]])->find();
        Db::name("join_wallet")->where(["join_id"=>$join_withdrawal["join_id"]])->update([
            "money"=>$join_wallet["money"]-$join_withdrawal["money"],
            "updated_at"=>time()
        ]);
    }
}

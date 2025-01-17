<?php


namespace app\server\join;


use think\facade\Db;

class Wthdrawal
{
    static function add($money,$join_id){
        Db::name("join_withdrawal")->insert([
            "money"=>$money,
            "join_id"=>$join_id,
            "created_at"=>time()
        ]);
    }
}

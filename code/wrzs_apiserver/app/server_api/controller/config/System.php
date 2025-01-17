<?php


namespace app\server_api\controller\config;


use app\common\code\SuccessCode;
use app\common\user\User;
use think\facade\Db;

class System
{

    public function info(){
        $join_id = User::uid();

       $data['store_num'] = Db::name("store")->where([
           'join_id'=>$join_id,'deleted_at'=>0])->count();
        $data['room_num'] = Db::name("room")->where([
            'join_id'=>$join_id,'deleted_at'=>0])->count();
        $data['device_num'] = Db::name("device")->where([
            'join_id'=>$join_id,'deleted_at'=>0])->count();

        $data['order_num'] = Db::name("order")->where([
            'join_id'=>$join_id,'order_status'=>3])->count();
      $res =  SuccessCode::$statusOk;
        $res['info']=$data;
        return json($res);
    }
}

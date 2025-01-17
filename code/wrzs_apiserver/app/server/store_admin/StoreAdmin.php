<?php


namespace app\server\store_admin;


use think\facade\Db;

class StoreAdmin
{
   static function manageMobileList($join_id,$store_id){

        $mobile  =[];
        //查询门店管理员
        $store_admin = Db::name('store_admin')->where(['join_id' => $join_id])->select();
        foreach ($store_admin as $vo) {
            $store_id_arr =json_decode($vo['store_id_arr'],true);
            if($store_id_arr &&  in_array($store_id,$store_id_arr)){
                if($vo['mobile'] &&  $vo['sms_status'] ){
                    $mobile[]=$vo['mobile'];
                }

            }
        }
        return $mobile;
    }
}

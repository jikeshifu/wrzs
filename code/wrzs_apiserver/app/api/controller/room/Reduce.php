<?php


namespace app\api\controller\room;


use think\facade\Db;

class Reduce
{

    public function list(){
        $room_id = input('post.room_id');
       $discount_reduce =Db::name("discount_reduce")->field(['full','reduce','title'])->where(['room_id'=>$room_id])->order('full desc')->cache(true,60)->select()->toArray();
        return json(['status' =>1, 'list' => $discount_reduce]);
    }
}

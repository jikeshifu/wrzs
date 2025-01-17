<?php


namespace app\api\controller\order;


use think\facade\Db;

class Reduce
{
    static function reduce($room_id, $price)
    {
        $reduce =false;
        $discount_reduce = Db::name('discount_reduce')->field(['room_id','full','reduce','title'])->where(['room_id' => $room_id])->order('full desc')->select()->toArray();
        foreach ($discount_reduce as $vo) {
           if($vo['full']<=$price){
               $price =$price -$vo['reduce'];
               $reduce =$vo;
                break;
           }
        }

        return ['price'=>$price,'reduce'=>$reduce];

    }
}

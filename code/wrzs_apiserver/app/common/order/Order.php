<?php


namespace app\common\order;


use app\common\kg\Kg;

class Order
{
    static function OrderInit($price,$type)
    {
        $data['price']=$price;
        $data['order_type']=$type;
        $data['order_sn']=Kg::app()->order()->orderSn();


    }
}

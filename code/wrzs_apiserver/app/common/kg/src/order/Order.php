<?php


namespace app\common\kg\src\order;


class Order
{
    /**
     * @return string
     * 生成订单sn
     */
    public function orderSn(){

        return date("YmdHis",time()).rand(10000000,99999999).rand(10000000,99999999);
    }
}

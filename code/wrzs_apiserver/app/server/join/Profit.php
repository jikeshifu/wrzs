<?php


namespace app\server\join;


class Profit
{
    static function orderType($list){
        foreach ($list as &$vo) {
            switch ($vo['order_type']) {
                case 'recharge';
                    $vo['order_type'] = "用户充值";
                    break;
                case 'rechargePackage';
                    $vo['order_type'] = "购买充值套餐";
                    break;
                case 'cabinet';
                    $vo['order_type'] = "用户购买售货柜";
                    break;
                case 'room';
                    $vo['order_type'] = "用户购买房间";
                    break;
            }
        }
        return $list;
    }
}

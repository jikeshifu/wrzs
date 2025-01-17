<?php


namespace app\module\member\server;


class MemberIntegralRecord
{
    /**
     * @param $member_id
     * @param $order
     * 添加增加积分
     */


    static function recordName($order_type){
        $record_name = "";
        switch ($order_type) {
            case 'roomOne';
                $record_name = "单次房间";
                break;
            case 'room';
                $record_name = "房间";
                break;
                case 'roomRenew';
                $record_name = "房间续费";
                break;
            case 'goods';
                $record_name = "商品";
                break;
            case 'cabinet';

                break;
            case 'rechargePackage':
            case 'recharge';
                $record_name = "重置";
                break;

        }

        return $record_name;
    }
    static function initRecord($member_id,$order,$number){
        $data["order_id"] =$order["order_id"];
        $data["member_id"] =$member_id;
        $data["number"] =$number;
        return $data ;

    }

    /**
     * @param $member_id
     * @param $order
     * @param $number
     * 添加增量
     */
    static function AddPlus($member_id, $order,$number)
    {
        $data =self::initRecord($member_id, $order,$number);
        $data["record_name"] ="购买".self::recordName($order["order_type"]);
        $data["type"] =1;

        \app\module\member\model\MemberIntegralRecord::Add($data);

    }

    /**
     * @param $member_id
     * @param $order
     * @param $number
     * 添加减少记录
     */
    static function AddReduce($member_id, $order,$number)
    {
        $data =self::initRecord($member_id, $order,$number);
        $data["record_name"] ="取消".self::recordName($order["order_type"]);
        $data["type"] =0;
        \app\module\member\model\MemberIntegralRecord::Add($data);

    }
}
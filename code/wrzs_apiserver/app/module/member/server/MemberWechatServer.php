<?php


namespace app\module\member\server;


use app\module\member\model\MemberWechat;
use think\facade\Db;

class MemberWechatServer
{


    /**
     * @param $member_id
     * @param $order
     * 增加积分
     */
    static function integralPlus($member_id, $order)
    {
    
        //获取用户信息
        $member_wechat = Db::name('member_wechat')->where(["member_id" => $member_id])->find();
        //增加积分  当前积分加上消费积分
        $integral =floor($order["order_price"]);
        $addIntegral =$member_wechat["integral"] + $integral;
        MemberWechat::Edit(["member_id" => $member_id],
            ["integral" => $addIntegral]
        );
        //增加增量记录
        MemberIntegralRecord::AddPlus($member_id,$order,$integral);

    }


    static function getIntegral($member_id){
        $memberWechatInfo = MemberWechat::where(["member_id"=>$member_id])->find();

        return $memberWechatInfo["integral"];
    }
}

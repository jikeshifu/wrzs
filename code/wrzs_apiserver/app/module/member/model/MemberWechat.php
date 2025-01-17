<?php


namespace app\module\member\model;


use think\Model;

class MemberWechat extends Model
{

    static function Edit($where, $upData = [])
    {
        $upData["updated_at"] = time();
        MemberWechat::where($where)->save($upData);

    }

    static function GetMemberInfoWMemberID($member_id)
    {
        $memberWechat = MemberWechat::where(["member_id" => $member_id])->find()->toArray();

        return $memberWechat;
    }
}
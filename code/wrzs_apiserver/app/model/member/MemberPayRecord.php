<?php


namespace app\model\member;


use think\Model;

class MemberPayRecord extends Model
{
    /**
     * 增加记录
     */
    static function increase($member_id,$money,$order_id,$title,$type=1)
    {

        $MemberPayRecord = new MemberPayRecord();
        $MemberPayRecord->insert([
            'title' => $title,
            'created_at' => time(),
            'price' =>$money,
            'member_id' => $member_id,
            'order_id' => $order_id,
            'type'=>$type
        ]);
    }

}

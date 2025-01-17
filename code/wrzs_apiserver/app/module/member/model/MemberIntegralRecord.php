<?php


namespace app\module\member\model;


use think\Model;

class MemberIntegralRecord extends Model
{

    static function Add($data = [])
    {

        $data["created_at"] = time();
        $MemberIntegralRecord = new MemberIntegralRecord();
        $MemberIntegralRecord->insert($data);
    }

    static function Edit($where, $upData = [])
    {
        $upData["updated_at"] = time();
        MemberIntegralRecord::where($where)->save($upData);

    }
}
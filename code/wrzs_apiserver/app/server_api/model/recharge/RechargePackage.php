<?php


namespace app\server_api\model\recharge;

use think\Model;

class RechargePackage extends Model
{

    // 设置字段信息
    public $schema = [
        'package_id' => 'int',
        'user_id' => 'int',
        'title' => 'varchar',
        'price' => 'double',
        'profit' => 'double',
        'created_at' => 'int',
        'updated_at' => 'int',
        'deleted_at' => 'int',
    ];
    public $error_msg = "";

    public function verification($data)
    {
        if (!$data['package_id']) {
            $this->error_msg = "package_id不能为空";
            return false;
        }
        return true;
    }

    public function addVerification($data)
    {
        if (!$data['title']) {
            $this->error_msg = "title不能为空";
            return false;
        }
        if (!$data['price']) {
            $this->error_msg = "price不能为空";
            return false;
        }
        if (!$data['profit']) {
            $this->error_msg = "profit不能为空";
            return false;
        }
        return true;
    }

    public function deletes($data)
    {


        $this->save(['deleted_at' => time()]);
    }
}

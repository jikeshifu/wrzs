<?php

namespace app\model\region;


use app\common\code\ErrorCode;
use think\facade\Db;
use think\Model;

class City extends Model
{

    public $field = [
        'city_id',
        'city_name',
        'longitude',
        'latitude',
        'is_default',

    ];
    public $error = null;


    public function add($data)
    {
        if (!$data['city_name']) {
            $this->error = ErrorCode::$adminCode[12];
            return;
        }
        $city = $this->where([
            'city_name' => $data['city_name'],
            "deleted_at" => 0
        ])->find();
        if ($city) {
            $this->error = ErrorCode::$adminCode[13];
            return;
        }
        $data['created_at'] = time();

        $this->insert($data);
        return;
    }

    public function edit($data)
    {

        if (!array_key_exists('city_id', $data) || !$data['city_id']) {
            $this->error = ErrorCode::$adminCode[6];
            return;
        }
        $data['updated_at'] = time();
        $this->where(['city_id' => $data['city_id']])->save($data);
        return;
    }

    public function del($data)
    {

        if (!array_key_exists('city_id', $data) || !$data['city_id']) {
            $this->error = ErrorCode::$adminCode[6];
            return;
        }
        $data['deleted_at'] = time();
        $this->where(['city_id' => $data['city_id']])->save($data);
        return;
    }
}

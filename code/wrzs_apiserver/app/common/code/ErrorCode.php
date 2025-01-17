<?php


namespace app\common\code;


class ErrorCode
{
    static function errorF($data){
        $data['status']=0;
        $data['error_code']=1000;
        if(!isset($data['msg']) ||!$data['msg'] ){
            $data['msg']="操作失败";
        }
        return $data;
    }
    static $errorStatus =['status'=>0,'error_code' => 1000, "msg" => "操作失败"];
    static $code = [
        0 => ['error_code' => 1000, "msg" => "操作失败"],
        1 => ['error_code' => 1001, "msg" => "优惠券已被使用"],
        2 => ['error_code' => 1002, "msg" => "该订单已经使用过优惠券"],
        3 => ['error_code' => 1003, "msg" => "余额不足"],
        4 => ['error_code' => 1004, "msg" => "订单异常"],
        5 => ['error_code' => 1005, "msg" => "订单已支付"],
        6 => ['error_code' => 1006, "msg" => "售货柜名称重复"],
        7 => ['error_code' => 1007, "msg" => "库存不足"],
        8 => ['error_code' => 1008, "msg" => "商品不存在"],
        9 => ['error_code' => 1009, "msg" => "领取钥匙失败"],
        10 => ['error_code' => 1010, "msg" => "订单未支付"],
        11 => ['error_code' => 1011, "msg" => "订单超时"],
        12 => ['error_code' => 1012, "msg" => "打开柜门失败"],
        13 => ['error_code' => 1013, "msg" => "手机号不能为空"],
        14 => ['error_code' => 1014, "msg" => "反馈内容不能为空"],
        15 => ['error_code' => 1015, "msg" => "下单失败"],
    ];
    static $adminCode = [
        0 => ['error_code' => 1000, "msg" => "账号密码不能为空"],
        1 => ['error_code' => 1001, "msg" => "账号密码错误"],
        2 => ['error_code' => 1002, "msg" => "账号已存在"],
        3 => ['error_code' => 1003, "msg" => "门店名称重复"],
        4 => ['error_code' => 1004, "msg" => "充值失败"],
        5 => ['error_code' => 1005, "msg" => "柜门号重复"],
        6 => ['error_code' => 1006, "msg" => "主键不能为空"],
        7 => ['error_code' => 1007, "msg" => "商品名称不能为空"],
        8 => ['error_code' => 1008, "msg" => "商id不能为空"],
        9 => ['error_code' => 1009, "msg" => "商品分类名称不能为空"],
        10 => ['error_code' => 1010, "msg" => "发货失败"],
        11 => ['error_code' => 1011, "msg" => "退款失败"],
        12 => ['error_code' => 1012, "msg" => "区域名称不能为空"],
        13 => ['error_code' => 1013, "msg" => "区域名称重复"],
        14 => ['error_code' => 1014, "msg" => "请选择定位"],
        15 => ['error_code' => 1015, "msg" => "超过允许开店数量"],
        16 => ['error_code' => 1016, "msg" => "手机号不能为空"],
        17 => ['error_code' => 1017, "msg" => "未找到手机号"],
    ];
}

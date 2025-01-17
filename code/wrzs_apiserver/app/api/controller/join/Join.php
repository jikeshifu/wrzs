<?php


namespace app\api\controller\join;


use app\common\code\SuccessCode;
use app\model\join\JoinApply;
use think\facade\Request;

class Join
{
    public function apply(){

            $Goods = new JoinApply();
            $data = Request::only($Goods->field);
            $Goods->add($data);
            if ($Goods->error) {
                return json($Goods->error);
            }
            return json(SuccessCode::$statusOk);

    }
}

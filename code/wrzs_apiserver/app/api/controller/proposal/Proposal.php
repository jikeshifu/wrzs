<?php


namespace app\api\controller\proposal;


use app\common\code\SuccessCode;
use think\facade\Request;

class Proposal
{
    public function add(){


        $Goods = new \app\model\proposal\Proposal();
        $data = Request::only($Goods->field);
        $Goods->add($data);
        if ($Goods->error) {
            return json($Goods->error);
        }
        return json(SuccessCode::$statusOk);
    }

}

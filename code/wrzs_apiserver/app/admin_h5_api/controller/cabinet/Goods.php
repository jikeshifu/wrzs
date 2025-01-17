<?php


namespace app\admin_h5_api\controller\cabinet;


use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\model\cabinet\CabinetGoods;
use think\facade\Db;

class Goods
{
    public function list()
    {
        $cabinet_id = input('cabinet_id');
        $CabinetGoods = CabinetGoods::where(["cabinet_id" => $cabinet_id])->select()->toArray();
        $cabinet =Db::name("cabinet")->where(['cabinet_id'=>$cabinet_id])->find();
        $data=  Kg::app()->device()->cabinet($cabinet['device_sn'])->state();
        foreach ($CabinetGoods as &$vo){
            $vo['lock_status']=$data['data'][$vo['cabinet_number']];

        }
        $res = SuccessCode::$statusOk;
        $res['list'] = $CabinetGoods;
        return json($res);

    }

    public function replenish()
    {
        $goods_id = input('goods_id');
        $CabinetGoods = CabinetGoods::where(["goods_id" => $goods_id])->with(['cabinet'])->find()->toArray();
        CabinetGoods::where(["goods_id" => $goods_id])->save(['stock'=>1]);
        $res = Kg::app()->device()->cabinet($CabinetGoods['cabinet']['device_sn'])->open($CabinetGoods['cabinet_number']);
        return json(SuccessCode::$statusOk);
    }

    public function replenishs(){
        $cabinet_id = input('cabinet_id');
        $CabinetGoods = \app\model\cabinet\CabinetGoods::where(["cabinet_id" => $cabinet_id])->where([
            'deleted_at'=>0,
            'stock'=>0
        ])->with(['cabinet'])->select()->toArray();


        foreach ($CabinetGoods as $vo){
           $res = Kg::app()->device()->cabinet($vo['cabinet']['device_sn'])->open($vo['cabinet_number']);
           if(!$res){
               CabinetGoods::where(["goods_id" => $vo['goods_id']])->save(['stock'=>1]);
           }
        }

        return json(SuccessCode::$statusOk);
    }
}

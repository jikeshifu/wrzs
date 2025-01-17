<?php


namespace app\api\controller\cabinet;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\user\User;
use app\model\cabinet\CabinetGoods;
use think\facade\Db;

class Order
{
    public function placeOrder()
    {
        $goods_id = input('goods_id');
        Db::startTrans();
        try {
            //查询售货柜商品
            $CabinetGoods = CabinetGoods::where(['goods_id' => $goods_id])->with(['cabinet'])->find()->toArray();
            if(!$CabinetGoods['stock']){
                return json(ErrorCode::$code[15]);
            }
            $price = $CabinetGoods['goods_price'];
            //创建主订单
            $Order = new \app\model\order\Order();
            $data['order_price'] = $price;
            $data['order_type'] = 'cabinet';
            $data['join_id'] = $CabinetGoods['cabinet']['join_id'];
            $data['room_id'] = $CabinetGoods['cabinet']['room_id'];
            $data['store_id'] = $CabinetGoods['cabinet']['store_id'];
            $order_id = $Order->add($data);

            //创建售货柜订单
            $this->orderCabinet($order_id, $CabinetGoods);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $err = ErrorCode::$code[0];
            $err['err_msg'] = $e->getMessage();
            return json($err);
        }
        $res = SuccessCode::$statusOk;
        $member_wallet = Db::name("member_wallet")->field('money')->where(['member_id' => User::uid(),'store_id'=>$data['store_id']])->find();
        $res['order_id'] = (int)$order_id;
        $res['money'] = $member_wallet['money'];
        return json($res);
    }

    public function orderCabinet($order_id, $CabinetGoods)
    {

        Db::name("order_cabinet")->insert([
            'order_id'=>$order_id,
            'goods_info'=>json_encode($CabinetGoods),
            'device_sn'=>$CabinetGoods['cabinet']['device_sn'],
            'cabinet_number'=>$CabinetGoods['cabinet_number']
        ]);

    }
}

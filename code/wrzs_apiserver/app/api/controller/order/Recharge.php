<?php


namespace app\api\controller\order;


use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\common\user\User;
use app\model\order\Order;
use think\facade\Db;

class Recharge
{

    public function placeOrder()
    {

        $recharge_money = input('post.recharge_money');


        //生成订单


        $order['order_type'] = 'recharge';
        $order['order_price'] = $recharge_money;
        //查询门店

        $Order = new Order();
        $order_id = $Order->add($order);


        Db::name('order_recharge_package')->insert([
            'order_id' => $order_id,
            'profit' => $recharge_money,

        ]);
        $order = Db::name('order')->where(['order_id' => $order_id])->find();


        return json(SuccessCode::statusOkf(['order_info'=>$order]));
    }

    public function package()
    {

        $recharge_package = Db::name('recharge_package')->where(['deleted_at' => 0])->select()->toArray();
        $res = SuccessCode::$code[0];
        $res['list'] = $recharge_package;
        return json($res);
    }


    public function placeOrderPackage()
    {

        $package_id = input('post.package_id');
        //查询套餐
        $recharge_package = Db::name('recharge_package')->where(['package_id' => $package_id, 'deleted_at' => 0])->find();

        //生成订单

        $data['order_type'] = 'rechargePackage';
        $data['order_price'] = $recharge_package['price'];

        $store_id =input('post.store_id');

        $Order = new Order();
        $order_id = $Order->add($data);
        Db::name('order_recharge_package')->insert([
            'order_id' => $order_id,
            'store_id'=>$store_id,
            'profit' => $recharge_package['price'] + $recharge_package['profit'],
        ]);
        $order = Db::name('order')->where(['order_id' => $order_id])->find();

        $res = SuccessCode::$code[0];
        $res['order_info'] = $order;

        return json($res);
    }
}

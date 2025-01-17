<?php


namespace app\api\controller\shop;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\kg\Kg;
use think\facade\Db;
use think\facade\Request;

class Order
{
    public $price = 0;

    public $error = null;
    public $goodsList = [];
    function placeOrder()
    {
        $this->goodsList = input('goods_list');

        Db::startTrans();
        try {
            //计算商品价格
            $this->prices();
            if ($this->error) {
                return json($this->error);
            }

            //创建主订单
            $Order = new \app\model\order\Order();
            $data['order_price'] =$this->price;
            $data['order_type'] ='goods';
            $order_id = $Order->add($data);
            //进入订单关闭接口
            $Beanstalkd = Kg::app()->Beanstalkd();
            //五分钟后未支付关闭订单
            $Beanstalkd->useTube('rrtOrderClose')->put((string)$order_id, 1024, 301);

            //写入商品订单信息
            foreach ($this->goodsList as &$vo){
                $vo['order_id']=$order_id;
            }
            Db::name('order_goods')->insertAll($this->goodsList);

            //保存收货人地址
            $shr = Request::only(['address','mobile','user_name']);
            $shr['order_id']=$order_id;
            Db::name('order_address')->insert($shr);

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $err =ErrorCode::$code[0];
            $err['err_msg']=$e->getMessage();
            $err['err_trace']=$e->getTraceAsString();
            $err['sql']=Db::name("goods")->getLastSql();
            return json($err);
        }
        $res = SuccessCode::$statusOk;

        $res['order_id'] = (int)$order_id;
        return json($res);

    }

    function prices()
    {

        foreach ($this->goodsList as &$vo) {

            $goods = Db::name("goods")->field('goods_id,goods_name,goods_price,goods_image,goods_stock')->where(['goods_id' => $vo['goods_id']])->lock(true)->find();

            if(!$goods){

                $this->error = ErrorCode::$code[8];
                return;
            }

            $vo['goods_info']=json_encode($goods);
            //判断库存是否足够
            if ($goods['goods_stock'] < $vo['goods_number']) {
                $errorMsg = ErrorCode::$code[7];

                $errorMsg['msg'] = $goods['goods_name'] . $errorMsg['msg'];
                $this->error = $errorMsg;
                return;
            }

            //扣除库存
            Db::name("goods")->where(['goods_id' => $vo['goods_id']])->update([
                'goods_stock'=>$goods['goods_stock']- $vo['goods_number']
            ]);
            unset($vo['goods_id']);
            $this->price += $goods['goods_price'] * $vo['goods_number'];


        }

    }
}

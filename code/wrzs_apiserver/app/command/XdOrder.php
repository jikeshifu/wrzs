<?php
declare (strict_types=1);

namespace app\command;

use app\common\cache\Redis;
use app\common\kg\Kg;
use app\common\member\MemberWallet;
use app\common\sms\Manage;
use app\model\member\MemberPayRecord;
use app\server\store_admin\StoreAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class XdOrder extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('xdOrder')
            ->setDescription('the order command');
    }

    protected function execute(Input $input, Output $output)
    {

        $Beanstalkd = Kg::app()->Beanstalkd();
        print_r('xdOrder-start');
        while (true) {
            //接收管道数据
            $job = $Beanstalkd->watch('rrt-xdOrder')->reserve();

            $info = json_decode($job->getData(), true);
            try {
                //数据处理
                $this->order($info['order_id'], $info['pay_type']);
                $Beanstalkd->delete($job);
            } catch (\Exception $e) {
                print_r('error-' . $e->getMessage());
                die;
            }
        }
        // 指令输出
        $output->writeln('xdOrder');
    }

    public function order($order_id, $pay_type)
    {
        //查询订单信息
        $order = Db::name('order')->where(['order_id' => $order_id])->find();
        Db::startTrans();
        try {
            //修改支付状态 支付时间 支付方式
            Db::name('order')->where(['order_id' => $order_id])->update([
                'pay_status' => 1,
                'pay_time' => time(),
                'pay_type' => $pay_type,
                'order_status' => 1,
            ]);
            //判断订单类型是否需要其他处理
            $title = "用户消费";

            switch ($order['order_type']) {
                case 'room';
                    $this->room($order_id);
                    $title = "房间";
                    break;
                case 'roomRenew';
                    $this->roomRenew($order_id);
                    $title = "房间续费";
                    break;

            }
            if ($pay_type != 'balance') {

                $title = '微信购买' . $title;
                //增加购买记录
                MemberPayRecord::increase($order['member_id'], $order['order_price'], $order_id, $title, 2);


            }


            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new \Exception($e->getMessage());


        }
    }


    public function rechargePackage($order)
    {
        //修改订单状态为完成
        Db::name('order')->where(['order_id' => $order['order_id']])->update(['order_status' => 3]);
        //查询充值多少
        $order_recharge_package = Db::name('order_recharge_package')->where([
            'order_id' => $order['order_id'],
        ])->find();

        //修改用户余额
        $member_wallet = Db::name("member_wallet")->where(['member_id' => $order['member_id']])->find();
        $money = $member_wallet['money'] + $order_recharge_package['profit'];
        Db::name("member_wallet")->where(['member_id' => $order['member_id']])->update([
            'money' => $money
        ]);
    }

    public function roomRenew($order_id)
    {
        //修改订单状态为完成
        Db::name('order')->where(['order_id' => $order_id])->update(['order_status' => 3]);
        //修改续费订单状态为完成
        Db::name('order_room_renew')->where(['order_id' => $order_id])->update(['order_status' => 3]);
        //查询续费订单详情
        $order_room_renew = Db::name('order_room_renew')->where(['order_id' => $order_id])->find();
        //查询订单房间信息
        $order_room = Db::name('order_room')->where(['order_id' => $order_room_renew['pid']])->find();
        //修改续费房间时间
        $end_time = $order_room['end_time'] + $order_room_renew['renew_time'];
        Db::name('order_room')->where(['order_id' => $order_room_renew['pid']])->update([
            'end_time' => $end_time,
        ]);
        //修改已预订时间段
        $redis = Redis::redis();
        $redis->hSet('room_id:' . $order_room['room_id'], (string)$order_room_renew['pid'], json_encode([
            'start_time' => $order_room['start_time'],
            'end_time' => $end_time,
        ]));

        //查询当前订单信息
        $order = Db::name('order')->where(['order_id' => $order_id])->find();
        //修改原来订单收入信息
        $pOrder = Db::name('order')->where(['order_id' => $order_room_renew['pid']])->find();
        Db::name('order')->where(['order_id' => $order_room_renew['pid']])->update(['order_profit' => $pOrder['order_profit'] + $order['order_price']]);
        //进入房间结束短信通知管道
        $Beanstalkd1 = Kg::app()->Beanstalkd();
        $endDelay = $end_time - time() - 900;
        if ($endDelay < 0) {
            $endDelay = 0;
        }
        $Beanstalkd1->useTube('roomEndSms')->put((string)$order_room_renew['pid'], 1024, $endDelay);

    }

    public function room($order_id)
    {
        //修改房间状态为待开始
        Db::name('order_room')->where(['order_id' => $order_id])->update(['room_status' => 1]);
        //查询房间
        $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();

        //修改房间已售+1
        $room = Db::name('room')->where(['room_id' => $order_room['room_id']])->find();
        //创建房间钥匙
        Db::name('room_key')->insert([
            'order_id' => $order_id,
            'member_id' => $order_room['member_id'],
            'created_at' => time(),
            'room_id' => $order_room['room_id'],
            'store_id' => $order_room['store_id'],
            'join_id' => $room['join_id']
        ]);

       $mobiles =   StoreAdmin::manageMobileList($room['join_id'],$order_room['store_id']);
       if($mobiles){
           Manage::order($mobiles,[
               $room['room_name'],
               date("Y-m-d H:i", $order_room['start_time']).'至' . date("Y-m-d H:i", $order_room['end_time'])
           ]);
       }

        Db::name('room')->where(['room_id' => $order_room['room_id']])->update([
            'room_sold' => $room['room_sold'] + 1
        ]);
        //修改门店已售加1
        $store = Db::name('store')->where(['store_id' => $order_room['store_id']])->find();
        Db::name('store')->where(['store_id' => $order_room['store_id']])->update([
            'store_sold' => $store['store_sold'] + 1
        ]);


        //修改优惠券为使用
        $order_coupons = Db::name('order_coupons')->where([
            'order_id' => $order_id
        ])->find();
        if ($order_coupons) {
            Db::name('member_coupons')->where([
                'member_coupons_id' => $order_coupons['member_coupons_id']
            ])->update(['use_time' => time()]);
        }
        //开始时间减去当前时间  在减去提前开始的时间等于需要延迟的秒数

        $redis = Redis::redis();

        $redis->hSet('room_id:' . $order_room['room_id'], (string)$order_id, json_encode([
            'start_time' => $order_room['start_time'],
            'end_time' => $order_room['end_time'],
        ]));
        $delay = $order_room['start_time'] - time();
        //结束时间减去当前时间等于结束需要延迟的时间
        $endDelay = $order_room['end_time'] - time();
        if ($delay < 0) {
            $delay = 0;
        }
        if ($endDelay < 0) {
            $endDelay = 0;
        }
        $Beanstalkd1 = Kg::app()->Beanstalkd();
        //进入房间开始管道 roomStart

        $Beanstalkd1->useTube('rrt-roomStart')->put((string)$order_id, 1024, $delay);
        //进入房间结束管道
        $Beanstalkd1->useTube('rrt-roomEnd')->put((string)$order_id, 1024, $endDelay);
        $room_info = json_decode($order_room['room_info'], true);

        //进入房间结束短信通知管道
        if ($room_info['room_type'] == 1) {
            $endDelay = $endDelay - 900;

        } else if ($room_info['room_type'] == 2) {
            $endDelay = $endDelay - 7200;
        }
        if ($endDelay < 0) {
            $endDelay = 0;
        }
        $Beanstalkd1->useTube('rrt-roomEndSms')->put((string)$order_id, 1024, $endDelay);
    }
}

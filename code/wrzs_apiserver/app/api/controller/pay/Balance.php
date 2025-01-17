<?php


namespace app\api\controller\pay;


use app\api\controller\pay\notify\Goods;
use app\api\controller\pay\notify\RoomOne;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\common\user\User;
use app\model\member\MemberWallet;
use think\facade\Db;

class Balance
{
    public function index()
    {

        $order_id = input('post.order_id');
        $order = Db::name('order')->where(['order_id' => $order_id])->find();
        if ($order['pay_status'] == 1) {
            return json(ErrorCode::$code[5]);
        }
        if ($order['order_status'] != 0) {
            return json(ErrorCode::$code[4]);

        }
        $member_id = User::uid();
        //如果钱包大于0
        if ($order['order_price'] > 0) {
            $member_wallet = Db::name('member_wallet')->where(['member_id' => $member_id])->find();
            if ($member_wallet['money'] < $order['order_price']) {
                return json(ErrorCode::$code[3]);
            }
        }


        if ($order['order_type'] == 'room') {
            //判断当前时间是否可选
            $order_room = Db::name('order_room')->where(['order_id' => $order_id])->find();
            $room = json_decode($order_room['room_info'], true);
            if ($room['reserve_status'] == 0) {
                //判断当前时间是否可选
                Kg::app()->room()->timeSlot($order_room['room_id'], $order_room['start_time'], $order_room['end_time']);
            }

        }

        Db::startTrans();
        try {
            if($order['order_price']>0){
                $member_wallet = Db::name('member_wallet')->where(['member_id' => $member_id])->find();
                if(!$member_wallet){
                    return json(ErrorCode::errorF(["msg"=>"余额不足"]));
                }
                $balance = $member_wallet['money'] - $order['order_price'];
                if ($balance < 0) {
                    return json(ErrorCode::$code[3]);
                }
            }






            // 提交事务
            Db::commit();
            $Beanstalkd = Kg::app()->Beanstalkd();
            switch ($order['order_type']) {
//                case 'roomOne';
//                    RoomOne::index($order, 'balance');
//                    break;
                case 'goods';
                    $title ="商品";
                    Goods::index($order,'balance');
                    break;
                case 'cabinet';
                    $title ="柜子";
                    $Beanstalkd->useTube('rrt-Cabinet')->put(json_encode(['order_id' => $order['order_id'], 'pay_type' => 'balance']));
                    break;
                default:
                    $title ="房间";
                    $xd_order_data = [
                        'order_id' => $order_id,
                        'pay_type' => 'balance'
                    ];
                    $Beanstalkd->useTube('rrt-xdOrder')->put(json_encode($xd_order_data));
            }
            if($order['order_price']>0){
                MemberWallet::reduce($member_id,$order['order_price'],$order['order_id'],'余额购买'.$title);
            }

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $err = ErrorCode::$code[0];
            $err['msg'] = $e->getMessage();

            return json($err);

        }

        return json(SuccessCode::$code[0]);
    }
}

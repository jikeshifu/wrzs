<?php


namespace app\api\controller\user;


use app\common\code\SuccessCode;
use app\common\user\User;
use think\facade\Db;

class Order
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $type = input('post.type', 1);
        $member_id = User::uid();

        $where = [
            'member_id' => $member_id,

        ];

        $Order = \app\api\model\order\Order::where($where)->where('order_type','in','room,roomOne')->with(['orderRoomBind','store']);

        if ($type == 2) {
            $Order->where('order_status', 'in', '1,2');
        } else if ($type == 3) {
            $Order->where(['order_status' => 3]);
        }elseif ($type == 4) {
            $Order->where(['order_status' => 4]);
        }else{
            $Order->where(['order_status' => 0]);
            $Order->where("created_at",">",time()-300);
        }

        $Ordercount = clone $Order;

        $list = $Order->page($page, $limit)->field('order_id,order_sn,created_at,order_price,store_id,order_status')->order('order_id desc')->select()->toArray();

        foreach ($list as &$vo) {

            $vo['syJs_time'] =   $vo['end_time']- time();
            if( $vo['syJs_time']<0){
                $vo['syJs_time'] =0;
            }
            $vo['syKs_time'] =   $vo['start_time']- time();
            if( $vo['syKs_time']<0){
                $vo['syKs_time'] =0;
            }
            $vo['room_info']=json_decode($vo['room_info'],true);
            $vo['room_name']= $vo['room_info']['room_name'];
            unset( $vo['room_info']);
        }
        $res =SuccessCode::$code[0];
        $res['list']=$list;
        $res['count']= $Ordercount->count();
        return json($res);

    }

    public function info()
    {
        $order_id = input('post.order_id');
        $member_id = User::uid();

        if (!$order_id) {
            return json(['status' => 0, 'msg' => 'order_id不能为空']);
        }

        $Order = \app\api\model\order\Order::where([
//            'member_id' => $member_id,
            'order_id' => $order_id
        ])->with(['orderRoomBind','orderRoomRenewList','orderReduce'])->find()->toArray();

        $Order['room_info'] = json_decode($Order['room_info'], true);

        $Order['room_info']['starting_time']=0.5;

        $Order['room_info']['room_images'] = json_decode($Order['room_info']['room_images'], true);
        foreach ($Order['orderRoomRenewList']  as &$vo){

            $arr=Db::name('order')->where(['order_id'=>$vo['order_id']])->find();
            $arr['number']=$vo['number'];
            $vo =$arr;
        }

       $store = Db::name('store')->where(['store_id'=>$Order['store_id']])->find();

        $Order['store']=$store;


        $order_coupons = Db::name('order_coupons')->where(['order_id'=>$order_id])->find();
        if($order_coupons){
            $Order['order_coupons']=$order_coupons;
        }else{
            $Order['order_coupons']=null;
        }


        $sy_time =time()-$Order['created_at'];
        if( $sy_time>300){
            $Order['sy_time'] =0;
        }else{
            $Order['sy_time'] =300-$sy_time;
        }
        $Order['syJs_time'] =   $Order['end_time']- time();
        if( $Order['syJs_time']<0){
            $Order['syJs_time'] =0;
        }
        $Order['syKs_time'] =   $Order['start_time']- time();
        if( $Order['syKs_time']<0){
            $Order['syKs_time'] =0;
        }

        $res =SuccessCode::$code[0];
        $res['info']=$Order;
        $member_wallet = Db::name('member_wallet')->where(['member_id' => $member_id])->find();
        if($member_wallet){
            $res['money'] = $member_wallet['money'];
        }else{
            $res['money'] = 0;
        }


        $res['kx_number']  =\app\api\controller\order\Room::WbRenewTimes($Order['order_id'],$Order['room_id']);
        if ($res['kx_number']<0){
            $res['kx_number'] =0;
        }

        return json($res);

    }
}

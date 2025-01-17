<?php


namespace app\admin_api\controller\member;


use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\kg\Kg;
use app\common\member\MemberWallet;
use app\common\user\User;
use app\server_api\model\member\MemberWechat;
use think\facade\Db;

class Member
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $public = input('post.public');

        $where = [

        ];
        $MemberWechat = MemberWechat::where($where);
        if ($public) {
            $MemberWechat->where(function ($query) use ($public) {
                $query->whereOr('nick_name', 'like', '%' . $public . '%');
                $query->whereOr('mobile', 'like', '%' . $public . '%');
            });
        }
        $MemberWechat->whereNotNull("mobile");

        $MemberWechatcount = clone $MemberWechat;
        $count = $MemberWechatcount->count();
        $list = $MemberWechat->page($page, $limit)->order(['nick_name'=>'desc','mobile'=>'desc','member_id'=>'desc'])->with(['memberWallet'])->select()->toArray();
        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);

    }

    /**
     * 后台给用户充值
     */
    public function recharge()
    {
        $price = input('post.price');
        $member_id = input('post.member_id');

        $member_wechat = Db::name('member_wechat')->where([
            'member_id' => $member_id,

        ])->find();
        if (!$member_wechat || !$price) {
            return json(ErrorCode::$adminCode[4]);
        }


        //生成订单
        $order['member_id'] = $member_id;
        $order['order_sn'] = Kg::app()->order()->orderSn();
        $order['order_type'] = 'adminRecharge';
        $order['pay_status'] = 1;
        $order['pay_time'] = time();
        $order['order_price'] = $price;
        $order['order_profit'] = $price;
        $order['order_status'] = 3;
        $order_id = Db::name('order')->insertGetId($order);
        MemberWallet::increase((int)$member_id, $price, $order_id,'后台充值');
        return json(SuccessCode::$statusOk);
    }
}

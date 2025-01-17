<?php


namespace app\model\member;


use app\server_api\model\store\Store;
use think\Model;

class MemberWallet extends Model
{

    function add($data = [])
    {
        $data['created_at'] = time();
        return $this->insertGetId($data);
    }

    /**
     * 增加余额
     */
    static function increase(int $member_id, float $money, int $order_id, string $title = "增加")
    {
        $model = new MemberWallet();
        $MemberWallet = $model->where(['member_id' => $member_id, ])->find();
        if (!$MemberWallet) {
            $wallet_id = $model->add(['member_id' => $member_id]);
            $MemberWallet = $model->where(['wallet_id' => $wallet_id])->find();
        }
        $m = $MemberWallet->toArray();
        $balance = $m['money'] + $money;
        $total = $m['total'] + $money;
        $MemberWallet->save([
            'money' => $balance,
            'total' => $total,
       'integral'=>$m['integral']+$money
        ]);
        //增加一个记录
        MemberPayRecord::increase($member_id, $money, $order_id, $title, 1);
    }

    /**
     * 减少余额
     */
    static public function reduce(int $member_id, float $money, int $order_id, string $title = "减少")
    {
        $model = new MemberWallet();
        $MemberWallet = $model->where(['member_id' => $member_id, ])->find();
        $m = $MemberWallet->toArray();
        $balance = $m['money'] - $money;
        $MemberWallet->save(['money' => $balance]);
        //增加一个记录
        MemberPayRecord::increase($member_id, $money, $order_id, $title, 2);
    }

    /**
     * 记录
     */
    static public function record()
    {


    }

    public function store()
    {
        return $this->hasOne(Store::class, 'store_id', 'store_id');
    }

}

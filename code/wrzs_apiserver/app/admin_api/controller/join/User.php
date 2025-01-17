<?php


namespace app\admin_api\controller\join;


use app\admin_api\controller\join\verification\JoinUserV;
use app\admin_api\controller\user\verification\UserV;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\model\join\JoinApply;
use app\model\order\Order;
use app\module\store\server\StoreServer;
use app\server\join\Profit;
use app\server\join\Wallet;
use think\facade\Db;
use think\facade\Request;

class User
{
    public $file_ld = [
        'account',
        'password',
        'user_name',
        'store_number',
        'status',
        'user_id'

    ];

    public function edit()
    {
        $data = Request::only($this->file_ld);
        //验证
        $JoinUserVRes = JoinUserV::edit($data);
        if ($JoinUserVRes['error']) {
            return json($JoinUserVRes['error']);
        }
        $data['pid'] = \app\common\user\User::uid();
        $data['updated_at'] = time();

        Db::name("join_user")->where(['user_id' => $data['user_id']])->update($data);
        return json(SuccessCode::$code[0]);
    }

    public function setPwd(){

        $user_id =input("user_id");

        Db::name("join_user")->where(['user_id' =>$user_id])->update([
            "password"=>self::pwhash(input("password"))
        ]);
        return json(SuccessCode::$code[0]);
    }


    static function pwhash($pw)
    {
        $options = [
            'cost' => 12,
        ];
        return password_hash($pw, PASSWORD_BCRYPT, $options);
    }
    public function add()
    {
        $data = Request::only($this->file_ld);
        //验证
        $JoinUserVRes = JoinUserV::add($data);
        if ($JoinUserVRes['error']) {
            return json($JoinUserVRes['error']);
        }
        $data['pid'] = \app\common\user\User::uid();
        $data['created_at'] = time();
        $data['password'] = $JoinUserVRes['password'];

        try {
            Db::startTrans();
            $join_id = Db::name("join_user")->insertGetId($data);
            //初始化钱包
            Wallet::add($join_id);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(ErrorCode::errorF(['msg'=>$e->getMessage()]));
        }
        return json(SuccessCode::$code[0]);
    }

    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $list = Db::name('join_user')->where(['deleted_at' => 0])->page($page, $limit)->order(['user_id' => 'desc'])->select()->toArray();
        $count = Db::name('join_user')->where(['deleted_at' => 0])->page($page, $limit)->order(['user_id' => 'desc'])->count();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function applyList()
    {
        $page = input('post.page', '1');
        $public = input('post.public');
        $limit = input('post.limit', '10');

        $model = JoinApply::where(['deleted_at' => 0]);
        if ($public) {
            $model->whereOr('name', 'like', '%' . $public . '%');
            $model->whereOr('mobile', 'like', '%' . $public . '%');
        }
        $modelCount = clone $model;
        $list = $model->page($page, $limit)->order(['id' => 'desc'])->select()->toArray();
        $count = $modelCount->count();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function applyListStatus()
    {
        $id = input('post.id', '1');

        Db::name('join_apply')->where(['id' => $id])->update(['status' => 1]);

        $res = SuccessCode::$statusOk;

        return json($res);
    }

    public function del()
    {
        $user_id = input('post.user_id', '1');
        try {
            Db::startTrans();
            StoreServer::Del($user_id);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(ErrorCode::errorF(['msg'=>$e->getMessage()]));
        }
        return json(SuccessCode::$statusOk);
    }

    public function status()
    {
        $user_id = input('post.user_id', '1');
        $data['status'] = input('post.status');
        Db::name("join_user")->where(['user_id' => $user_id])->update($data);

        return json(SuccessCode::$statusOk);
    }

    public function profit()
    {

        $page = input('post.page', '1');

        $limit = input('post.limit', '10');
        $end_time = input('post.end_time');
        $join_id = input('post.join_id');

        $start_time = input('post.start_time');
        if (!$start_time) {
            $start_time = strtotime(date("Y/m/") . '1');
        }
        $model = Order::where([
            'order_status' => 3,
            'join_id' => $join_id,
            'pay_type' => 'wechat',
            'order_type' => ['recharge', 'rechargePackage', 'room', 'cabinet']
        ])->page($page, $limit);

        $model->where('created_at', '>=', $start_time);

        if ($end_time) {
            $end_time = $end_time + 86359;
            $model->where('created_at', '<=', $end_time);
        }
        $profit = $model->sum('order_profit');
        $list = $model->order("order_id desc")->select()->toArray();

        $count = $model->count();
        $res = SuccessCode::$statusOk;

        $res['list'] = Profit::orderType($list);
        $res['profit'] = $profit;

        $res['count'] = $count;
        return json($res);
    }
}

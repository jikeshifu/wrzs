<?php


namespace app\admin_api\controller\recharge;


use app\common\code\SuccessCode;
use app\server_api\model\recharge\RechargePackage;
use think\facade\Db;
use think\facade\Request;

class Package
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');


        $where = [

            'deleted_at' => 0
        ];
        $RechargePackage = RechargePackage::where($where);


        $RechargePackagecount = clone $RechargePackage;
        $count = $RechargePackagecount->count();
        $list = $RechargePackage->page($page, $limit)->order(['package_id' => 'desc'])->select()->toArray();
        $res =SuccessCode::$statusOk;

        $res['list']=$list;
        $res['count']=$count;
        return json($res);
    }

    public function edit()
    {

        $RechargePackage = new RechargePackage();
        $data = Request::param(array_keys($RechargePackage->schema));
        if (!$RechargePackage->verification($data)) {
            return json(['status' => 0, 'msg' => $RechargePackage->error_msg]);
        }
        $RechargePackageEdit = RechargePackage::where(['package_id' => $data['package_id']]);
        $RechargePackageEdit->save($data);
        return json(SuccessCode::$statusOk);
    }

    public function add()
    {
        $RechargePackage = new RechargePackage();
        $data = Request::param(array_keys($RechargePackage->schema));
        if (!$RechargePackage->addVerification($data)) {
            return json(['status' => 0, 'msg' => $RechargePackage->error_msg]);
        }
        $data['user_id'] = \app\common\user\User::uid();
        $data['created_at'] = time();
        $RechargePackage->save($data);
        return json(SuccessCode::$statusOk);
    }

    public function del()
    {
        $package_id = Request::param('package_id');

        Db::name('recharge_package')->where(['package_id'=>$package_id])->update(['deleted_at' => time()]);

        return json(SuccessCode::$statusOk);
    }
}

<?php


namespace app\server_api\controller\recharge;


use app\server_api\model\recharge\RechargePackage;
use think\facade\Request;

class Package
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $wxapp_id = \app\common\user\User::wxappid();
        $where = [
            'wxapp_id' => $wxapp_id,
            'deleted_at' => 0
        ];
        $RechargePackage = RechargePackage::where($where);


        $RechargePackagecount = clone $RechargePackage;
        $count = $RechargePackagecount->count();
        $list = $RechargePackage->page($page, $limit)->order(['package_id' => 'desc'])->select()->toArray();

        return json(['status' => 1, 'msg' => '操作成功', 'list' => $list, 'count' => $count]);
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
        return json(['status' => 1, 'msg' => '更新成功']);
    }

    public function add()
    {
        $RechargePackage = new RechargePackage();
        $data = Request::param(array_keys($RechargePackage->schema));
        if (!$RechargePackage->addVerification($data)) {
            return json(['status' => 0, 'msg' => $RechargePackage->error_msg]);
        }
        $data['wxapp_id'] = \app\common\user\User::wxappid();
        $data['created_at'] = time();
        $RechargePackage->save($data);
        return json(['status' => 1, 'msg' => '添加成功']);
    }

    public function del()
    {
        $package_id = Request::param('package_id');
        $RechargePackage = RechargePackage::where(['package_id' => $package_id])->find();
        $RechargePackage->deletes($package_id);
        return json(['status' => 1, 'msg' => '删除成功']);
    }
}

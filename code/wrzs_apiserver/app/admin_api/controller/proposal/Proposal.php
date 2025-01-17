<?php


namespace app\admin_api\controller\proposal;


use app\common\code\SuccessCode;
use think\facade\Db;
use think\facade\Request;

class Proposal
{
    public function list()
    {

        $page = input('post.page', '1');
        $limit = input('post.limit', '10');

        $list = \app\model\proposal\Proposal::where(['deleted_at' => 0])->with(['member'])->page($page, $limit)->order(['id' => 'desc'])->select()->toArray();
        $count = \app\model\proposal\Proposal::where(['deleted_at' => 0])->count();
        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function status()
    {
        $id = input('post.id', '1');


         \app\model\proposal\Proposal::where(['id' => $id])->save(['status'=>1]);
        $res = SuccessCode::$statusOk;
        return json($res);
    }
}

<?php


namespace app\server_api\controller\device;

use app\common\code\SuccessCode;
use app\common\kg\Kg;
use think\facade\Db;

class Card
{
    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $device_id = input('post.device_id');
        $card_sn = input('post.card_sn' );
        $where = [
            'deleted_at' => 0,

            'device_id' => $device_id
        ];

        if($card_sn){
            $where['card_sn']=$card_sn;
        }
        $device = Db::name('device_card')->where($where);

        $admin_user_count = clone $device;
        $count = $admin_user_count->count();
        $list = $device->page($page, $limit)->order(['card_id' => 'desc'])->select()->toArray();

        $res =SuccessCode::$statusOk;
        $res['list']=$list;
        $res['count']=$count;
        return json($res);

    }

    public function add()
    {
        $device_id = input('post.device_id');
        $card_sn = input('post.card_sn');
        $end_time = input('post.end_time');
        $device = Db::name('device')->where(['device_id' => $device_id])->find();
      $res =  Kg::app()->device()->lock($device['device_sn'])->cardAdd($card_sn, $end_time);
      if($res){

          return json(['status'=>0,'error_code' => 1123, "msg" => $res]);
      }
        Db::name('device_card')->insert([
            'device_id' => $device_id,
            'card_sn' => $card_sn,
            'end_time' => $end_time,
            'created_at' => time(),
            'device_sn'=>$device['device_sn']
        ]);
        $res =SuccessCode::$statusOk;
        return json($res);
    }

    public function del()
    {
        $card_id = input('post.card_id');
        $device_card = Db::name('device_card')->where(['card_id' => $card_id])->find();
        $device = Db::name('device')->where(['device_id' => $device_card['device_id']])->find();

        Kg::app()->device()->lock($device['device_sn'])->cardDel($device_card['card_sn']);

        Db::name('device_card')->where(['card_id' => $card_id])->update([
            'deleted_at' => time(),
        ]);
        $res =SuccessCode::$statusOk;
        return json($res);
    }
}

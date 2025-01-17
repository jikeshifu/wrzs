<?php


namespace app\api\controller\user;


use app\api\model\room\RoomKey;
use app\common\code\ErrorCode;
use app\common\code\SuccessCode;
use app\common\device\lock\LockFactory;
use app\common\file\FileYs;
use app\common\kg\Kg;
use app\common\user\User;
use app\module\hardwareCloud\HardwareClout;
use app\server_api\model\device\Device;
use think\facade\Db;

class Room
{
    public function list()
    {

        $uid = User::uid();

        $room_key = RoomKey::where([
            'member_id' => $uid,
            'deleted_at' => 0,

        ])->with(['orderRoom', 'store', 'cabinet'])->select()->toArray();

        foreach ($room_key as &$vo) {
            if ($vo['cabinet']) {
                $device_state = Kg::app()->device()->cabinet($vo['cabinet']['device_sn'])->state();

                $cabinet_goods = Db::name("cabinet_goods")->where([
                    'cabinet_id' => $vo['cabinet']['cabinet_id'],
                    'deleted_at' => 0,
                ])->select()->toArray();
                if(is_array($cabinet_goods)){
                      foreach ($cabinet_goods as &$cabinet_goodsVo){
                          $cabinet_goodsVo['lock_status']=$device_state['data'][$cabinet_goodsVo['cabinet_number']];

                      }
                }
                $vo['cabinet_goods'] = $cabinet_goods;
            }

            $vo['start_time'] = $vo['orderRoom']['start_time'];
            $vo['end_time'] = $vo['orderRoom']['end_time'];
            $vo['syJs_time'] = $vo['end_time'] - time();
            $vo['syKs_time'] = $vo['start_time'] - time();
            if ($vo['syKs_time'] < 0) {
                $vo['syKs_time'] = 0;
            }
            $vo['uid'] = $vo['orderRoom']['member_id'];
            $vo['room_status'] = (int)$vo['orderRoom']['room_status'];
            $vo['room_info'] = json_decode($vo['orderRoom']['room_info'], true);

            $vo['room_info']['room_image'] =FileYs::getThumb($vo['room_info']['room_image'] ,'1136','640');
            $vo['room_info']['room_images'] = json_decode($vo['room_info']['room_images'], true);
            unset($vo['orderRoom']);

        }
        $res = SuccessCode::$code[0];
        $res['list'] = $room_key;
        return json($res);
    }

    public function openLock()
    {
        $key_id = input('post.key_id');
        $RoomKey = RoomKey::where(['key_id' => $key_id, 'deleted_at' => 0])->find();
        if (!$RoomKey) {
            return json(['status' => 0, 'msg' => '没有权限']);
        }
        $order_room = Db::name('order_room')->where(['order_id' => $RoomKey['order_id']])->find();


        if (($order_room['start_time'] - 900) > time()) {
            return json(['status' => 0, 'msg' => '未到开始时间', 'time' => ($order_room['start_time'] - 900)]);
        }

        if ($order_room['end_time'] < time() || $order_room['room_status'] == 3 || $order_room['room_status'] == 4) {
            return json(['status' => 0, 'msg' => '房间已经结束']);
        }
        $device = Db::name('device')->where([
            'room_id' => $RoomKey['room_id'],
            'deleted_at' => 0,
            'device_type' => 1,
        ])->find();
        if (!$device) {
            return json(['status' => 0, 'msg' => '房间未绑定设备']);
        }


        $userInfo = User::userInfo();
        $room_info = Db::name('room')->where(['room_id' => $RoomKey['room_id']])->find();

        //获取今天营业开始时间
        $work_time_start = $room_info['work_time_start'];

        $work_time_start_s = strtotime(date("Y-m-d ", time()) . $work_time_start);
//        if (time() < $work_time_start_s) {
//            return json(['status' => 0, 'msg' => '开门失败还未开始营业']);
//        }

        //获取今天营业结束时间
        $work_time_end = $room_info['work_time_end'];
        $work_time_end_s = strtotime(date("Y-m-d ", time()) . $work_time_end);

//        if (time() > $work_time_end_s) {
//            return json(['status' => 0, 'msg' => '开门失败营业时间已结束']);
//        }

        $res=  HardwareClout::WifiLock()->OpenLock($device['device_sn']);
        if ($res["err"]) {

            Db::name('device_record')->insert([
                'username' => $userInfo['nick_name'] . $userInfo['member_id'] . '-用户开门',
                'title' => '开门失败',
                'created_at' => time(),
                'device_name' => $device['device_name'],
                'join_id' => $device['join_id'],
                'device_sn' => $device['device_sn'],
                'room_name' => $room_info['room_name'],
            ]);
            $errRes = ErrorCode::$code[0];
            $errRes['error'] = $res;
            return json($errRes);
        }

        Db::name('device_record')->insert([
            'username' => $userInfo['nick_name'] . $userInfo['member_id'] . '-用户开门',
            'title' => '开门成功',
            'created_at' => time(),
            'device_name' => $device['device_name'],
            'join_id' => $device['join_id'],
            'device_sn' => $device['device_sn'],
            'room_name' => $room_info['room_name'],
        ]);
        return json(SuccessCode::$code[0]);
    }

    //打开公共门
    public function openPublicLock()
    {
        $key_id = input('post.key_id');
        $RoomKey = RoomKey::where(['key_id' => $key_id, 'deleted_at' => 0])->find();
        if (!$RoomKey) {
            return json(['status' => 0, 'msg' => '没有权限']);
        }
        $order_room = Db::name('order_room')->where(['order_id' => $RoomKey['order_id']])->find();

//        if (($order_room['start_time'] - 900) > time()) {
//            return json(['status' => 0, 'msg' => '未到开始时间', 'time' => ($order_room['start_time'] - 900)]);
//        }

        if ($order_room['end_time'] < time() || $order_room['room_status'] == 3 || $order_room['room_status'] == 4) {
            return json(['status' => 0, 'msg' => '房间已经结束']);
        }
        $device = Db::name('device')->where([
            'room_id' => $RoomKey['room_id'],
            'deleted_at' => 0,
            'device_type' => 2,
        ])->find();
        if (!$device) {
            return json(['status' => 0, 'msg' => '房间未绑定设备']);
        }

        $res =  HardwareClout::WifiLock()->OpenLock($device['device_sn']);

        $userInfo = User::userInfo();
        $room_info = Db::name('room')->where(['room_id' => $RoomKey['room_id']])->find();
        if ($res["err"]) {

            Db::name('device_record')->insert([
                'username' => $userInfo['nick_name'] . $userInfo['member_id'] . '-用户开门',
                'title' => '开门失败',
                'created_at' => time(),
                'device_name' => $device['device_name'],
                'join_id' => $device['join_id'],
                'device_sn' => $device['device_sn'],
                'room_name' => $room_info['room_name'],
            ]);
            $errRes = ErrorCode::$code[0];
            $errRes['error'] = $res;
            return json($errRes);
        }

        Db::name('device_record')->insert([
            'username' => $userInfo['nick_name'] . $userInfo['member_id'] . '-用户开门',
            'title' => '开门成功',
            'created_at' => time(),
            'device_name' => $device['device_name'],
            'join_id' => $device['join_id'],
            'device_sn' => $device['device_sn'],
            'room_name' => $room_info['room_name'],
        ]);
        return json(SuccessCode::$code[0]);
    }
    public function open($device){
        if ($device['gw_sn']) {
            $oplock = Kg::app()->device()->lock($device['device_sn'])->wgStart($device['gw_sn']);
        } else {
            $oplock = Kg::app()->device()->lock($device['device_sn'])->start();
        }
        return $oplock;
    }
    public function qrOpenLock()
    {
        $device_id = input('post.device_id');


        $device = Device::where(["device_id" => $device_id])->find();
        if (!$device) {
            $res = SuccessCode::$statusOk;
            $res['data'] = null;
            return json($res);

        }
        //获取用户信息
        $userInfo = User::userInfo();
        //如果是大门

        if ($device['device_type'] == 2) {
            $key = Db::name('room_key')->where([
                'store_id' => $device['store_id'],
                'member_id' => $userInfo['member_id'],
                'deleted_at' => 0
            ])->find();
        } else {
            $key = Db::name('room_key')->where([
                'room_id' => $device['room_id'],
                'member_id' => $userInfo['member_id'],
                'deleted_at' => 0
            ])->find();
        }



        if ($key) {

            $oplock =null;
            $room_info = Db::name('room')->where(['room_id' => $device['room_id']])->find();
            if ($device['device_type'] == 2) {
                $room_info['room_name'] = "门店大门";
                $oplock =$this->open($device);
            }else{
               $order_room = Db::name("order_room")->where(['order_id'=>$key['order_id']])->find();
               if($order_room['start_time']<(time()+600)){
                   $oplock =$this->open($device);
               }else{

                   return json(ErrorCode::errorF(['msg'=>"未到开门时间","data"=>$order_room]));
               }

            }


            if ($oplock) {

                Db::name('device_record')->insert([
                    'username' => $userInfo['nick_name'] . $userInfo['member_id'] . '-用户开门',
                    'title' => '开门失败',
                    'created_at' => time(),
                    'device_name' => $device['device_name'],
                    'join_id' => $device['join_id'],
                    'device_sn' => $device['device_sn'],
                    'room_name' => $room_info['room_name'],
                ]);

            } else {
                Db::name('device_record')->insert([
                    'username' => $userInfo['nick_name'] . $userInfo['member_id'] . '-用户开门',
                    'title' => '开门成功',
                    'created_at' => time(),
                    'device_name' => $device['device_name'],
                    'join_id' => $device['join_id'],
                    'device_sn' => $device['device_sn'],
                    'room_name' => $room_info['room_name'],
                ]);
            }
            $res = SuccessCode::$statusOk;
            $res['room_id'] = null;
            return json($res);
        } else {

            $res = SuccessCode::$statusOk;
            $res['room_id'] = $device['room_id'];
            return json($res);
        }
    }

    /**
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * 分享钥匙
     */
    public function shareKey()
    {
        $uid = User::uid();
        $order_id = input('post.order_id');
        $order_room = Db::name('order_room')->where(['order_id' => $order_id, 'member_id' => $uid])->find();

        if (!$order_room) {
            return json(['status' => 0, 'msg' => '没有权限']);
        }
        if ($order_room['end_time'] < time() || $order_room['room_status'] == 3 || $order_room['room_status'] == 4) {
            return json(['status' => 0, 'msg' => '房间已经结束']);
        }
        //创建房间钥匙
        $join_id = input('post.join_id');
        $room_key = Db::name('room_key')->insertGetId([
            'order_id' => $order_id,
            'member_id' => 0,
            'created_at' => time(),
            'room_id' => $order_room['room_id'],
            'store_id' => $order_room['store_id'],
            'join_id' => $join_id,
        ]);
        $res = SuccessCode::$statusOk;
        $res['key_id'] = $room_key;
        return json($res);

    }

    public function getKey()
    {
        $uid = User::uid();
        $key_id = input('post.key_id');
        $room_key = Db::name('room_key')->where(['key_id' => $key_id])->find();
        $res = SuccessCode::$statusOk;
        if (!$room_key){
            return json($res);
        }
        if ( $room_key['member_id'] == $uid) {

            return json($res);
        }
        if ($room_key['member_id'] != 0) {
            return json(ErrorCode::$code[9]);
        }

        $room_key = Db::name('room_key')->where(['member_id' => $uid, 'order_id' => $room_key['order_id']])->find();
        if ($room_key) {
            return json(ErrorCode::$code[9]);
        }
        $room_key = Db::name('room_key')->where(['key_id' => $key_id])->update(['member_id' => $uid]);
        $res = SuccessCode::$statusOk;

        return json($res);
    }
}

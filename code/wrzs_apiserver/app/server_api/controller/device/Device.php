<?php


namespace app\server_api\controller\device;


use app\common\code\SuccessCode;
use app\common\data\Data;
use app\common\device\air_switch\AirSwitchFactory;
use app\common\device\lock\LockFactory;

use app\common\kg\Kg;
use app\common\user\User;
use app\module\hardwareCloud\HardwareClout;
use app\server_api\controller\Base;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use think\facade\Db;
use think\facade\Request;

class Device extends Base
{
    public $file_ld = [
        'device_name',
        'device_sn',
        'device_type',
        'room_id',
        'voice',
        'volume',
        'gw_sn'

    ];

    public function add()
    {
        $data = Data::clearEmpty($this->request->only($this->file_ld));
        if (!$data['device_sn']) {
            return json(['status' => 0, 'msg' => '设备sn不能为空']);
        }
        //注册设备
        $res = HardwareClout::App()->Register(($data['device_sn']));

        if ($res["err"]) {
            return json(['status' => 0, 'msg' => $res["err"]]);
        }
        if(mb_substr($data['device_sn'],0,3)=="W89"){
            $ActivateRes = HardwareClout::WifiLock()->Activate($data['device_sn']);
            if ($ActivateRes["err"]) {
                return json(['status' => 0, 'msg' => $res["err"]]);
            }
        }


        $join_id = User::uid();
        $room = Db::name('room')->where([
            'room_id' => $data['room_id'],
        ])->find();
        $data['store_id'] = $room['store_id'];
        $data['join_id'] = $join_id;
        //设备二维码

       Db::name('device')->insertGetId($data);

        return json(SuccessCode::$statusOk);
    }


    public function list()
    {
        $page = input('post.page', '1');
        $limit = input('post.limit', '10');
        $room_id = input('post.room_id');
        $public = input('post.public');
     $join_id = User::uid();
        $where = [
            'deleted_at' => 0,
            'join_id' => $join_id,
            'room_id' => $room_id
        ];

        $device = Db::name('device')->where($where);
        $device_type = input('post.device_type');
        if ($device_type) {
            $device->where(['device_type' => $device_type]);
        }

        if ($public) {

            $device->where(function ($query) use ($public) {
                $query->whereOr('device_name', 'like', '%' . $public . '%')
                    ->whereOr('device_sn', 'like', '%' . $public . '%')
                    ->whereOr('gw_sn', 'like', '%' . $public . '%');
            });
        }
        $admin_user_count = clone $device;
        $count = $admin_user_count->count();
        $list = $device->page($page, $limit)->order(['device_id' => 'desc'])->select()->toArray();
        foreach ($list as &$vo) {
            $vo['on_line'] =        HardwareClout::App()->OnLineGet($vo['device_sn']);


            $writer = new PngWriter();
            $vo['qr_img'] ='/qr/'.$vo['device_id'].'sbewm.png'  ;
// Create QR code
            $qrCode = QrCode::create("https://was.weishequ.com/qr/?device_id=".$vo['device_id'])
                ->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                ->setSize(300)
                ->setMargin(10)
                ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));



            $result = $writer->write($qrCode);
            $result->saveToFile(root_path() . "/public/qr/".$vo['device_id'].'sbewm.png');
            $dataUri = $result->getDataUri();


        }

        $res = SuccessCode::$statusOk;
        $res['list'] = $list;
        $res['count'] = $count;
        return json($res);
    }

    public function del()
    {
        $device_id = input('post.device_id');
        if ($device_id) {

            Db::name('device')->where(['device_id' => $device_id])->update(['deleted_at'=>time()]);
        }
        return json(SuccessCode::$statusOk);
    }


    public function voice()
    {
        $device_id = input('post.device_id');
        $voice = input('post.voice');
        $volume = input('post.volume');


        if ($device_id) {
            $device = Db::name('device')->where(['device_id' => $device_id])->find();

            if (substr($device['device_sn'], 0, 5) == "WMJ62" || substr($device['device_sn'], 0, 5) == "KGE62") {
                //设备二维码
                $qr = 'https://was.weishequ.com/qr/?device_id=' . $device_id . '&state=';
                Kg::app()->device()->lock($device['device_sn'])->qr($qr);

              $res =  Db::name('device')->where(['device_id' => $device_id])->update(['qr' => $qr]);

            }
            Kg::app()->device()->lock($device['device_sn'])->voice($voice, $volume);
            Db::name('device')->where(['device_id' => $device_id])->update([
                'voice' => $voice,
                'volume' => $volume
            ]);
        }
        return json(SuccessCode::$statusOk);
    }


    public function qr()
    {
        $device_id = input('post.device_id');
        $qr_status = input('post.qr_status');
        $qr_time = input('post.qr_time');
        if ($device_id) {

            $qr = 'https://rrt-server.seekr.cc/qr/?device_id=' . $device_id . '&state=';
            Db::name('device')->where(['device_id' => $device_id])->update([
                'qr_status' => $qr_status,
                'qr_time' => $qr_time,
                'qr' => $qr
            ]);
        }
        return json(SuccessCode::$statusOk);
    }

    public function status()
    {
        $device_id = input('post.device_id');
        $status = input('post.status');
        if ($device_id) {

            Db::name('device')->where(['device_id' => $device_id])->update(['status' => $status]);
        }
        return json(SuccessCode::$statusOk);
    }

}

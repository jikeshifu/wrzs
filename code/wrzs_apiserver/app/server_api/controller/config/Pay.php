<?php


namespace app\server_api\controller\config;


use app\common\user\User;
use think\facade\Db;

class Pay
{
    public function info(){
        $wxapp_id = User::wxappid();
        $pay_config =  Db::name('pay_config')->where(['wxapp_id' => $wxapp_id])->find();
        if(!$pay_config){
            Db::name('pay_config')->insertGetId(['wxapp_id' => $wxapp_id]);
            $pay_config =  Db::name('pay_config')->where(['wxapp_id' => $wxapp_id])->find();
        }
        $cert_file = root_path() . '/path/to/pay/' . $wxapp_id . '_cert.pem';
        $key_file = root_path() . '/path/to/pay/' . $wxapp_id . '_key.pem';
        if(file_exists($cert_file)){
            $pay_config['cert_pem']  =file_get_contents($cert_file);
        }
        if(file_exists($key_file)){
            $pay_config['key_pem']  = file_get_contents($key_file);
        }

        return json(['status' => 1, 'info' => $pay_config]);
    }
    public function edit()
    {
        //cert.pem
        $wxapp_id = User::wxappid();
        $cert_pem = input('post.cert_pem');
        $key_pem = input('post.key_pem');
        $cert_file = root_path() . '/path/to/pay/' . $wxapp_id . '_cert.pem';
        $key_file = root_path() . '/path/to/pay/' . $wxapp_id . '_key.pem';
        file_put_contents($cert_file, $cert_pem);
        file_put_contents($key_file, $key_pem);
        $data['app_id']= input('post.app_id');
        $data['mch_id']= input('post.mch_id');
        $data['key']= input('post.key');
       Db::name('pay_config')->where(['wxapp_id' => $wxapp_id])->update($data);

        return json(['status' => 1, 'msg' => '设置成功']);

    }
}

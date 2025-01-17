<?php


namespace app\server_api\controller\config;


use app\common\cache\Redis;
use app\common\curl\Curl;
use app\common\kg\Kg;
use app\common\user\User;
use think\facade\Db;

class MiniProgram
{

    public $authorizer_access_token;

    public function __construct()
    {
        $wxapp_id = User::wxappid();
        $pay_config = Db::name('pay_config')->where(['wxapp_id' => $wxapp_id])->find();
        $miniProgram = Kg::app()->watch()->miniProgram($pay_config['app_id']);
        $getToken = $miniProgram->access_token->getToken();

        $this->authorizer_access_token = $getToken['authorizer_access_token' . $pay_config['app_id']];
        $qrcodejumpdownload = $this->qrcodejumpdownload();

        $path =root_path() . '/public/qr/'.$wxapp_id .'/';
        $file = $path. $qrcodejumpdownload['file_name'];
        //判断效验文件是否存在
        if (!file_exists($file)) {
            mkdir($path);
            file_put_contents($file, $qrcodejumpdownload['file_content']);
        }
    }

    /**
     * @return \think\response\Json
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     */
    public function qrcodejumpget()
    {


        //查询appid


        $res = Curl::curlJson("https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpget?access_token=" . $this->authorizer_access_token, ['access_token' => $this->authorizer_access_token]);

        if ($res['errcode'] == 0) {
            return json(['status' => 1, 'list' => $res['rule_list']]);
        } else {
            return json(['status' => 0, 'error' => $res]);
        }

    }

    /**
     * @return \think\response\Json
     * 增加或修改二维码规则
     */
    public function qrcodejumpadd()
    {


        $data['prefix'] = input('post.prefix');
        $data['access_token'] = $this->authorizer_access_token;
        $data['permit_sub_rule'] = 1;
        $data['path'] = input('post.path');
        $data['open_version'] = 1;
        $data['is_edit'] = input('post.is_edit');

        $res = Curl::curlJson("https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpadd?access_token=" . $this->authorizer_access_token, $data);

        if ($res['errcode'] == 0) {
            return json(['status' => 1, 'msg' => "操作成功"]);
        } else {
            return json(['status' => 0, 'error' => $res,'data'=>$data]);
        }
    }
    /**
     * @return \think\response\Json
     * 删除二维码规则
     */
    public function qrcodejumpdelete()
    {


        $data['prefix'] = input('post.prefix');
        $data['access_token'] = $this->authorizer_access_token;


        $res = Curl::curlJson("https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdelete?access_token=" . $this->authorizer_access_token, $data);

        if ($res['errcode'] == 0) {
            return json(['status' => 1, 'msg' => "操作成功"]);
        } else {
            return json(['status' => 0, 'error' => $res]);
        }
    }
    /**
     * 发布已设置的二维码规则
     */
    public function qrcodejumppublish()
    {
        $data['prefix'] = input('post.prefix');
        $data['access_token'] = $this->authorizer_access_token;

        $res = Curl::curlJson("https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumppublish?access_token=" . $this->authorizer_access_token, $data);

        if ($res['errcode'] == 0) {
            return json(['status' => 1, 'msg' => '发布成功']);
        } else {
            return json(['status' => 0, 'error' => $res]);
        }
    }


    /**
     * 获取效验文件
     */
    public function qrcodejumpdownload()
    {

        $data['access_token'] = $this->authorizer_access_token;

        $res = Curl::curlJson("https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdownload?access_token=" . $this->authorizer_access_token, $data);

        return $res;
    }

    /**
     * 获取服务器域名
     */

    public function get_modify_domain(){

        $data['action'] = "get";

        $res = Curl::curlJson("https://api.weixin.qq.com/wxa/modify_domain?access_token=" . $this->authorizer_access_token, $data);
        if($res['errcode']==0){
            return json(['status' =>1,'msg'=>"获取成功", 'res' => $res]);
        }else{
            return json(['status' => 0, 'error' => $res]);
        }

    }

    /**
     * 更新服务器域名
     */

    public function set_modify_domain(){

        $data['action'] = "set";
        $data['requestdomain'] = input('post.requestdomain',[]);
        $data['wsrequestdomain'] = input('post.wsrequestdomain',[]);
        $data['uploaddomain'] = input('post.uploaddomain',[]);
        $data['downloaddomain'] = input('post.downloaddomain',[]);
        $res = Curl::curlJson("https://api.weixin.qq.com/wxa/modify_domain?access_token=" . $this->authorizer_access_token, $data);
        if($res['errcode']==0){
            return json(['status' =>1,'msg'=>"修改成功", 'res' => $res]);
        }else{
            return json(['status' => 0, 'error' => $res]);
        }

    }
}

<?php


namespace app\server_api\controller\config;


use app\common\cache\Redis;
use app\common\curl\Curl;
use app\common\kg\Kg;
use app\common\user\User;
use think\facade\Db;

class MiniProgramMenu
{

    public function info()
    {
        $wxapp_id = User::wxappid();
        $config_miniprogram_menu = Db::name("config_miniprogram_menu")->where([
            'wxapp_id' => $wxapp_id
        ])->find();
        if ($config_miniprogram_menu) {
            $config_miniprogram_menu['menu'] =json_decode($config_miniprogram_menu['menu'],true);

        }else{
            $config_miniprogram_menu['menu'] = [
                "首页",
                "管理",
                "我的"
            ];
            $id = Db::name("config_miniprogram_menu")->insertGetId([
                'menu' => json_encode($config_miniprogram_menu['menu']),
                'wxapp_id'=>$wxapp_id,

            ]);
            $config_miniprogram_menu['id'] = (int)$id;
        }
        return json(['status' => 1, 'info' => $config_miniprogram_menu]);
    }

    public function edit()
    {
        $menu = input('post.menu');
        if(!$menu){
            return json(['status' =>0, 'msg' => "菜单不能为空"]);
        }

        foreach ($menu as $vo){
            if(!$vo){
                return json(['status' =>0, 'msg' => "菜单不能为空"]);
            }
        }

        $wxapp_id = User::wxappid();
        $config_miniprogram_menu = Db::name("config_miniprogram_menu")->where([
            'wxapp_id' => $wxapp_id
        ])->update([
            'menu'=>json_encode($menu),
            'updated_at'=>time()
        ]);

        return json(['status' => 1, 'msg' => "修改成功"]);
    }
}

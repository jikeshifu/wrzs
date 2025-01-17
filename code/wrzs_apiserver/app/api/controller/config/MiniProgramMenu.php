<?php


namespace app\api\controller\config;


use think\facade\Db;

class MiniProgramMenu
{

    public function info()
    {
        $wxapp_id =  input('post.wxapp_id');;
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


}

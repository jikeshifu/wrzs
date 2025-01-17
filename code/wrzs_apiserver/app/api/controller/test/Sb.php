<?php


namespace app\api\controller\test;


interface Sb{
    /**
     * @param $sn
     * @return mixed
     * 注册设备
     */
    public function register($sn);
    //打开设备
    public function open($sn);
    //关闭设备
    public function stop($sn);
}

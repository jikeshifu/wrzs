<?php


namespace app\common\kg\src\device;


interface DeviceInterface
{
    /**
     * @param $sn
     * @return mixed
     * 注册设备
     */

    public function register($sn);

    /**
     * @param $sn
     * @return mixed
     * 打开设备
     */
    public function open();

    /**
     * @param $sn
     * @return mixed
     * 关闭设备
     */
    public function stop();
}

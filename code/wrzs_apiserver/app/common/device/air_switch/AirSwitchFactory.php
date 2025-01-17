<?php


namespace app\common\device\air_switch;


class AirSwitchFactory
{
    /**
     * @param $sn 打开电源
     * @param int $volume 音量
     *
     * @param string $msg 提示语音
     * 控制4g空开
     */
    static function start($sn, $volume = 2,  $msg = '欢迎光临，已为您打开电源')
    {
        $AirSwitch = new AirSwitch($sn);
        return $AirSwitch->start($volume, $msg);
    }
    /**
     * @param $sn 停止电源
     * @param int $volume 音量
     *
     * @param string $msg 提示语音
     * 控制4g空开
     */
    static function stop($sn, $volume = 2,  $msg = '欢迎光临，已为您打开电源')
    {
        $AirSwitch = new AirSwitch($sn);
        return $AirSwitch->stop($volume, $msg);
    }
    /**
     * 注册设备
     * @param $sn 设备序列号
     * @return mixed
     */
    static function regswitch($sn)
    {
        $AirSwitch = new AirSwitch($sn);
        return $AirSwitch->regswitch();
    }

}

<?php

namespace app\common\device\lock;
class LockFactory
{

    /**
     * @param $sn
     * @return array
     * 设备注册
     */
    static function reglock($sn)
    {
        $Lock = new Lock();
        return $Lock->reglock($sn);
    }

    /**
     * @param $sn
     * @param $qrcodeurl
     * @return array
     * 设置二维码
     */
    static function lcdconfig($sn,$qrcodeurl){
        $Lock = new Lock();
        return $Lock->lcdconfig($sn,$qrcodeurl);
    }

    /**
     * @param $sn
     * @param $openttscontent
     * @param int $volume
     * @return array
     * 配置云语音接口
     */
    static function audioconfig($sn,$openttscontent,$volume=5){
        $Lock = new Lock();
        return $Lock->audioconfig($sn,$openttscontent,$volume);
    }

    static function oplock($sn){
        $Lock = new Lock();
        return $Lock->oplock($sn);
    }
}

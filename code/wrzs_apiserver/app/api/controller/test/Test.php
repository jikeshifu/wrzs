<?php


namespace app\api\controller\test;


use app\common\device\air_switch\AirSwitchFactory;
use app\common\device\lock\LockFactory;
use app\common\kg\Kg;
use think\facade\Db;

class Test
{

    function index()
    {
        $air_switch =Kg::app()->device()->airSwitch('W68590686392');
//        $air_switch->start();
        $air_switch->stop();


    }


}

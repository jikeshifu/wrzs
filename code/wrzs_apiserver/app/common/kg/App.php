<?php


namespace app\common\kg;


use app\common\kg\src\beanstalkd\Beanstalkd;

use app\common\kg\src\device\DeviceFactory;
use app\common\kg\src\order\Order;
use app\common\kg\src\room\Room;
use app\common\kg\src\sms\Sms;
use app\common\kg\src\watch\Watch;

class App
{
    public function watch()
    {
        return new Watch();
    }

    public function Beanstalkd()
    {
        $Beanstalkd = new Beanstalkd();
        return $Beanstalkd->beanstalkd();
    }

    public function room()
    {

        return new Room();
    }

    public function order()
    {

        return new Order();
    }

    public function sms()
    {
        return new Sms();
    }

    public function device()
    {
        return new DeviceFactory();
    }


}

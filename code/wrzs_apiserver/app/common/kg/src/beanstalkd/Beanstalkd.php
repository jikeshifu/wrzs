<?php


namespace app\common\kg\src\beanstalkd;


use Pheanstalk\Pheanstalk;

class Beanstalkd
{
    static public $Pheanstalk=null;
    public function __construct()
    {
    }

    public function beanstalkd(){

        if(!self::$Pheanstalk){
            self::$Pheanstalk = Pheanstalk::create('127.0.0.1');
        }

        return self::$Pheanstalk;
    }

}

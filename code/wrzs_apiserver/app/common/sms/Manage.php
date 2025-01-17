<?php


namespace app\common\sms;


class Manage
{

    /**
     * 用户下单
     * @param $PhoneNumberSet ['138XXXXXXXX','139XXXXXXXX']
     * @param array $TemplateParamSet ['房间名','时间']
     */
    static function order($PhoneNumberSet,$TemplateParamSet=[]){
        $res =  Tx::smsPush($PhoneNumberSet,1218906,$TemplateParamSet);
//      print_r($res);
    }
    /**
     * 订单结束
     * @param $PhoneNumberSet ['138XXXXXXXX','139XXXXXXXX']
     * @param array $TemplateParamSet ['房间名']
     */
    static function orderEnd($PhoneNumberSet,$TemplateParamSet=[]){
      $res =  Tx::smsPush($PhoneNumberSet,1218901,$TemplateParamSet);
//      print_r($res);
    }



}

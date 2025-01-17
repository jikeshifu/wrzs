<?php


namespace app\common\sms;


class Member
{
    /**
     * @param $PhoneNumberSet ['138XXXXXXXX','139XXXXXXXX']
     * @param array $TemplateParamSet ['房间名','剩余时间']
     */
    static function renew($PhoneNumberSet,$TemplateParamSet=[]){
      $res =  Tx::smsPush($PhoneNumberSet,1216259,$TemplateParamSet);
//      print_r($res);
    }
}

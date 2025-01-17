<?php


namespace app\common\data;


class Data
{
    static function clearEmpty($data){

        foreach ($data as $key=>$vo){
            if(!is_array($vo)){
                if(strlen($vo) ==0){
                    unset($data[$key]);
                }
            }

        }
        return $data;
    }


}

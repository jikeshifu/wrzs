<?php


namespace app\common;


class Common
{
    static function search($obj,$field=[]){
        foreach ($field as $key=>$vo){
            if($vo){
                $obj->where($key,'like','%'.$vo.'%');
            }

        }
        return $obj;
    }

    static function jqSearch($obj,$field=[]){
        foreach ($field as $key=>$vo){
            if(strlen($vo)){

                $obj->where([$key=>$vo]);
            }

        }

        return $obj;
    }
}

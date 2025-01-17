<?php


namespace app\common\map;


class Map
{


    /**
     * 百度转腾讯坐标转换
     * @param $a Latitude
     * @param $b Latitude
     * @return array
     */
   static  function coordinate_switch($a,$b)//百度转腾讯坐标转换  $a = Latitude , $b = Latitude
    {
        $x = (double)$b - 0.0065;
        $y = (double)$a - 0.006;
        $x_pi = 3.14159265358979324*3000/180;
        $z = sqrt($x * $x+$y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y,$x) - 0.000003 * cos($x*$x_pi);
        $gb = number_format($z * cos($theta),15);
        $ga = number_format($z * sin($theta),15);
        return ['Latitude'=>substr($ga,0,9),'Longitude'=>substr($gb,0,10)];
    }



    /*
* 中国正常GCJ02坐标---->百度地图BD09坐标
* 腾讯地图用的也是GCJ02坐标
* @param double $lat 纬度
* @param double $lng 经度
*/

   static  function Convert_GCJ02_To_BD09($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng;
        $y = $lat;
        $z =sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta) + 0.0065;
        $lat = $z * sin($theta) + 0.006;
        return array('lng'=>$lng,'lat'=>$lat);
    }


    /*
    * 百度地图BD09坐标---->中国正常GCJ02坐标
    * 腾讯地图用的也是GCJ02坐标
    * @param double $lat 纬度
    * @param double $lng 经度
    * @return array();
    */

    static  function Convert_BD09_To_GCJ02($lat,$lng){
        $x_pi = 3.14159265358979324 * 3000.0 / 180.0;
        $x = $lng - 0.0065;
        $y = $lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $x_pi);
        $lng = $z * cos($theta);
        $lat = $z * sin($theta);
        return array('lng'=>$lng,'lat'=>$lat);
    }
}

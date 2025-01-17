<?php


namespace app\module\hardwareCloud\deivce;


use app\module\hardwareCloud\server;

class horn
{

    /**
     * @param $device_sn
     * 扬声器
     */
    public function Play($device_sn,$ttscontent,$volume=5)
    {

        $res = server::Request("mqtt/send", [
            "device_sn" => $device_sn,
            "data" => [
                "cmd_type" => "play",
                "info"=>[
                    "tts" =>$ttscontent,
                    "inner" =>10,
                    "volume" => $volume,
                ]
            ]
        ]);
        if ($res["code"] != 0) {
            return ["err" => $res["msg"]];
        }
 

        return ["err" => null,"data"=>$res["data"]];
    }
}

<?php


namespace app\common\kg\src\cache;

use app\common\cache\Redis;
use Psr\SimpleCache\CacheInterface;

class MyCustomCache implements CacheInterface
{
    public function get($key, $default = null)
    {

        // your code

        $Redis = Redis::redis();
        if($key=="easywechat.kernel.access_token"){
            return $Redis->hGetAll($key) ;
        }
        return $Redis->get($key);
    }

    public function set($key, $value, $ttl = null)
    {
        // your code

        $Redis = Redis::redis();

        if(is_array($value)){
            foreach ($value as $valuekey=>$vo){
                $value[$valuekey.'ttl']=time()+6000;
            }


            return $Redis->hMSet($key,$value);
        }
        return $Redis->set($key,$value);
    }

    public function delete($key)
    {
        // your code
        $Redis = Redis::redis();
        return $Redis->del($key);
    }

    public function clear()
    {
        // your code
    }

    public function getMultiple($keys, $default = null)
    {
        // your code
    }

    public function setMultiple($values, $ttl = null)
    {
        print_r($values);die;
        // your code
    }

    public function deleteMultiple($keys)
    {
        // your code

    }

    public function has($key)
    {
        // your code

        $Redis = Redis::redis();
        return $Redis->hGetAll($key) ;
    }
}

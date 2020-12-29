<?php
/**
 * @author Dawnc
 * @date   2020-12-29
 */

namespace App\Lib;


use Wms\Lib\Redis;

class Session
{
    public function new($data)
    {
        $redis = Redis::getInstance();
        $sid   = self::sid();
        $redis->setex("TOKEN:" . $sid, 3600, json_encode($data));
    }

    public function set($token)
    {

    }

    private function sid()
    {
        return md5(uniqid() + time());
    }

}

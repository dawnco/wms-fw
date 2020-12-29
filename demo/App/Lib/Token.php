<?php
/**
 * @author Dawnc
 * @date   2020-12-29
 */

namespace App\Lib;


use Wms\Lib\Redis;

class Token
{

    private $key;
    private $expire = 3600;
    /**
     * @var \Redis
     */
    private $redis;

    public function __construct($redis, $key)
    {
        $this->key = "TOKEN:" . $key;


        $this->redis = $redis;


        if ($this->redis->exists($this->key)) {
            $this->redis->expire($this->key, $this->expire);
        }

    }

    public function new($data)
    {
        $this->key = self::sid();
        $this->redis->hMSet($this->key, $data);
        $this->redis->expire($this->key, $this->expire);
        return $this;
    }

    public function set($key, $val)
    {
        $this->redis->hSet($this->key, $key, $val);
        return $this;

    }

    public function del($key)
    {
        return $this->redis->hDel($this->key, $key);
        return $this;
    }

    private function sid()
    {
        return md5(uniqid() + time());
    }

}

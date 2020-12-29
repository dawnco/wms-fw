<?php
/**
 * @author Dawnc
 * @date   2020-12-29
 */

namespace App\Lib;


class Token
{

    private $key;
    private $expire = 3600;

    private $prefix = "TOKEN:";
    /**
     * @var \Redis
     */
    private $redis;

    public function __construct($redis, $key)
    {
        $this->key = $this->prefix . $key;


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

    public function get($key)
    {
        if ($this->key == $this->prefix) {
            return null;
        }
        return $this->redis->hGet($this->key, $key) ?: null;
    }

    public function del($key)
    {
        return $this->redis->hDel($this->key, $key);
        return $this;
    }

    public function getTokenKey()
    {
        return str_replace($this->prefix, "", $this->key);
    }

    private function sid()
    {
        return md5(uniqid() . time());
    }

}

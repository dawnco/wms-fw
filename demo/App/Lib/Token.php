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
        $this->key = $key;

        $this->redis = $redis;

        if ($this->redis->exists($this->saveKey())) {
            $this->redis->expire($this->saveKey(), $this->expire);
        }

    }

    public function destroy()
    {
        $this->redis->del($this->saveKey());
    }

    public function new($data)
    {
        $this->key = self::sid();
        $this->redis->hMSet($this->saveKey(), $data);
        $this->redis->expire($this->saveKey(), $this->expire);
        return $this;
    }

    public function set($key, $val)
    {
        $this->redis->hSet($this->saveKey(), $key, $val);
        return $this;
    }

    public function get($key)
    {
        if (!$this->key) {
            return null;
        }
        return $this->redis->hGet($this->saveKey(), $key) ?: null;
    }

    public function del($key)
    {
        return $this->redis->hDel($this->saveKey(), $key);
        return $this;
    }

    public function getTokenKey()
    {
        return $this->key;
    }

    private function sid()
    {
        return md5(uniqid() . time());
    }

    private function saveKey()
    {
        return $this->prefix . $this->key;
    }

}

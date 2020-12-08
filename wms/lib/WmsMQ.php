<?php

/**
 * redis 简单消息队列
 * @author Dawnc
 * @date   2020-07-17
 */

namespace wms\lib;


use wms\fw\Conf;
use wms\fw\Exception;

class WmsMQ
{

    protected static function key($name)
    {
        return 'WmsMQ:' . $name;
    }

    protected static function redis()
    {
        return Redis::getInstance(Conf::get("db.queue"));
    }

    /**
     * 入队列
     * @param string $name   队列名称
     * @param mix    $data   数据
     * @param int    $size   队列大小  0 不限制
     * @param int    $expire 队列过期时间 0 不限制
     * @return mixed
     */
    public static function push($name, $data, $size = 0, $expire = 0)
    {
        $redis = self::redis();
        $key   = self::key($name);

        if ($size == 1) {
            throw  new Exception("queue size must greater than 1");
        }

        $script = <<<EOT
            local key  = KEYS[1]
            local value = ARGV[1]
            local max  = tonumber(ARGV[2])
            local expire  = tonumber(ARGV[3])
            
            local result = nil

           -- redis.log(redis.LOG_NOTICE, tostring(max))
            if max > 0 then
               -- 有限制队列长度
                local size = redis.call('lLen', key)
                if size >= max then
                    redis.call('lTrim', key, 0, max - 2)
                end           
            end
            
            result = redis.call('lPush', key, value)
            
            -- 设置过期时间
            if expire > 0 then
                redis.call('expire', key, expire)
            end
            
            return result
EOT;

        $sha = $redis->script('load', $script);
        $ret = $redis->evalSha($sha, [
            $key,
            wms_json_encode($data),
            $size,
            $expire
        ], 1);
        //echo $redis->getLastError();

        return $ret;
    }

    /**
     * 出队列
     * @param $name
     * @return bool|mixed
     */
    public static function pop($name)
    {
        $redis   = self::redis();
        $key     = self::key($name);
        $message = $redis->rPop($key);
        return $message === false ? false : json_decode($message, true);
    }

    /**
     * 队列数据
     * @param $name
     * @return array
     */
    public static function list($name, $start = 0, $end = -1)
    {
        $redis = self::redis();
        $key   = self::key($name);

        $data = $redis->lRange($key, $start, $end);
        if ($data) {
            $_d = [];
            foreach ($data as $v) {
                $_d[] = json_decode($v, true);
            }
            return $_d;
        } else {
            return [];
        }
    }

    public static function size($name)
    {
        $redis = Redis::getInstance(Conf::get("db.queue"));
        $key   = self::key($name);
        return $redis->lLen($key);
    }
}

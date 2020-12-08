<?php
/**
 *  redis 并发锁
 * @author Dawnc
 * @date   2020-07-27
 */

namespace wms\lib;


class Mutex
{
    private $redis  = null;
    private $prefix = "mutex:";
    private $name   = '';

    // 锁过期时间 预防逻辑处理卡死没解锁
    private $lockExpire = 8000;  // 毫秒 1s = 1000ms


    /**
     * @param      $name 锁的名称
     * @param null $redis
     */
    public function __construct($name, $redis = null)
    {
        $this->name  = $name;
        $this->redis = $redis ?: Redis::getInstance();
    }

    /**
     * 获得锁的 执行 success 没有执行 fail
     * @param      $success
     * @param null $fail
     * @throws \Exception
     */
    public function call($success, $fail = null)
    {
        $lock = $this->lock();

        $exception = null;
        $result    = null;

        if ($lock) {
            try {
                $result = $success();
            } catch (\Exception $exp) {
                $exception = $exp;
            } finally {
                $this->unlock();
            }
            if ($exception) {
                throw $exception;
            }
        } else {
            if (is_callable($fail)) {
                $fail();
            }
        }


    }


    /**
     * 等待一个操作,未获得锁则继续等待
     * @param $callback
     * @return mixed
     * @throws \Exception
     */
    public function synchronized($callback)
    {
        $lock = $this->lock();

        while (!$lock) {
            // 1微秒（micro second）是百万分之一秒
            usleep(50000);
            $lock = $this->lock();
        }

        $exception = null;
        try {
            $result = $callback();
        } catch (\Exception $exp) {
            $exception = $exp;
        } finally {
            $this->unlock();
        }
        if ($exception) {
            throw $exception;
        }

        return $result;
    }

    protected function lock()
    {
        return $this->redis->set($this->prefix . $this->name, 1, ['nx', 'px' => $this->lockExpire]);
    }

    protected function unlock()
    {
        return $this->redis->del($this->prefix . $this->name);
    }

}

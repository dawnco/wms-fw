<?php
/**
 * @author Dawnc
 * @date   2020-12-18
 */

namespace wms\lib;


class Log
{
    public static function debug($msg, ...$arg)
    {
        Logger::instance()->debug($msg, ...$arg);
    }

    public static function info($msg, ...$arg)
    {
        Logger::instance()->info($msg, ...$arg);
    }

    public static function warning($msg, ...$arg)
    {
        Logger::instance()->warning($msg, ...$arg);
    }

    public static function error($msg, ...$arg)
    {
        Logger::instance()->error($msg, ...$arg);
    }

}

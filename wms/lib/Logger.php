<?php
/**
 * @author Dawnc
 * @date   2020-12-18
 */

namespace wms\lib;


use wms\fw\Conf;

class Logger
{

    private $loggerName;
    private $dir;

    private $level = 1;


    private static $INS = [];

    private function __construct($loggerName = "")
    {
        $this->loggerName = $loggerName;
        $this->dir        = Conf::get('app.log_dir') ?: "/tmp";
        $level            = [
            'debug'   => 1,
            'info'    => 2,
            'warning' => 3,
            'error'   => 4,
        ];
        $this->level      = $level[Conf::get('app.log_level')] ?? 1;
    }

    /**
     * @param string $loggerName 日志名称
     * @return Logger
     */
    public static function instance($loggerName = 'app')
    {
        if (!isset(self::$INS[$loggerName])) {
            self::$INS[$loggerName] = new self($loggerName);
        }
        return self::$INS[$loggerName];
    }

    public function debug($msg, ...$arg)
    {
        if ($this->level <= 1) {
            $this->log("debug", vsprintf($msg, $arg));
        }
    }

    public function info($msg, ...$arg)
    {
        if ($this->level <= 2) {
            $this->log("info", vsprintf($msg, $arg));
        }
    }

    public function warning($msg, ...$arg)
    {
        if ($this->level <= 3) {
            $this->log("warning", vsprintf($msg, $arg));
        }
    }

    public function error($msg, ...$arg)
    {
        if ($this->level <= 4) {
            $this->log("error", vsprintf($msg, $arg));
        }
    }

    protected function log($level, $msg)
    {
        $str = date("Y-m-d H:i:s") . " [$level] " . $msg . "\n";

        $file = implode("", [$this->dir, "/", $this->loggerName, "-", $level, date("-Y-m-d"), ".log"]);

        file_put_contents($file, $str, FILE_APPEND);
    }
}
<?php
/**
 * @author Dawnc
 * @date   2020-12-18
 */

namespace Wms\Lib;


use Wms\Fw\Conf;

class Logger
{

    private $loggerName;
    private $dir;

    private $level = 1;


    private static $INS = [];

    private function __construct($loggerName = "")
    {
        $this->loggerName = $loggerName;
        $this->dir        = Conf::get('app.log.dir') ?: (APP_PATH . "/Runtime");
        $level            = [
            'debug'   => 1,
            'info'    => 2,
            'warning' => 3,
            'error'   => 4,
        ];
        $this->level      = $level[Conf::get('app.log.level')] ?? 1;
    }

    /**
     * @param string $loggerName 日志名称
     * @return Logger
     */
    public static function instance($loggerName = 'App')
    {
        if (!isset(self::$INS[$loggerName])) {
            self::$INS[$loggerName] = new self($loggerName);
        }
        return self::$INS[$loggerName];
    }

    public function debug($msg, ...$arg)
    {
        if ($this->level <= 1) {
            $this->log("debug", $this->format($msg, $arg));
        }
    }

    public function info($msg, ...$arg)
    {
        if ($this->level <= 2) {
            $this->log("info", $this->format($msg, $arg));
        }
    }

    public function warning($msg, ...$arg)
    {
        if ($this->level <= 3) {
            $this->log("warning", $this->format($msg, $arg));
        }
    }

    public function error($msg, ...$arg)
    {
        if ($this->level <= 4) {
            $this->log("error", $this->format($msg, $arg));
        }
    }

    protected function format($msg, $arg)
    {
        foreach ($arg as $k => $v) {
            if ($v instanceof \Throwable) {
                $arg[$k] = sprintf("ExceptionCode: %s ExceptionMessage: %s\n    ExceptionTrace:\n    %s",
                    $v->getCode(),
                    $v->getMessage(),
                    str_replace("\n", "\n    ", $v->getTraceAsString())
                    );
            }
        }
        return vsprintf($msg, $arg);
    }

    protected function log($level, $msg)
    {

        // bof 找到哪里发生的日志
        $call = [];

        $array = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        foreach ($array as $row) {
            if (isset($row['file'])) {
                $file = str_replace("\\", "/", $row['file']);
                if (!strpos($file, "Logger.php") && !strpos($file, "Log.php") && !strpos($file, "Exception.php")) {
                    $call[] = "    {$row['file']}({$row['line']}):{$row['function']}";
                }
            }
        }

        // eof 找到哪里发生的日志
        $str = sprintf("%s [%s] %s\n    LoggerTrace:\n%s\n\n", date("Y-m-d H:i:s"), $level, $msg, implode("\n", $call));

        $file = implode("", [$this->dir, "/", $this->loggerName, "-", $level, date("-Y-m-d"), ".log"]);
        file_put_contents($file, $str, FILE_APPEND);
    }
}

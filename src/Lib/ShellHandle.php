<?php
/**
 * @author Dawnc
 * @date   2020-10-13
 */

namespace Wms\Lib;


abstract class ShellHandle
{

    abstract protected function handle($param = null);

    public function start($param = null)
    {
        $start = microtime(true);
        $this->handle($param);
        $end = microtime(true);
        $this->log(sprintf("耗时: %.4f 秒", $end - $start));
    }


    protected function log($msg, $type = 'success')
    {
        if ($type == 'error') {
            // 红色字
            echo "\033[32;31m $msg \033[0m\n";
            return;
        } else {
            // 绿色
            echo "\033[32;40m $msg \033[0m\n";
            return;
        }
    }

}

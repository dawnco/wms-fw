<?php
/**
 * @author Dawnc
 * @date   2020-06-29
 */

namespace Wms\Lib;

use Swoole\Event;
use Swoole\Http\Server;
use Swoole\Timer;

/**
 * 持续集成
 * 服务地址 http://127.0.0.1:8008
 * Class CI
 * @package lib
 */
class CI
{
    public function start()
    {

        swoole_set_process_name("sw-ci-git-pull");
        $http = new Server("0.0.0.0", 8008);
        $http->set(array(
            'reactor_num' => 1,
            'worker_num' => 1,
            'backlog' => 128,
            'max_request' => 50,
            'dispatch_mode' => 1,
            'daemonize' => 1,
            'log_level' => SWOOLE_LOG_ERROR,
            'log_file' => "/data/log/git-pull.log",
        ));
        $http->set(['enable_coroutine' => true]);
        $http->on('request', function ($request, $response) {
            Event::defer(function () {
                $this->exec();
            });
            $response->end("ok");
        });

        $http->start();
    }


    public function timer()
    {
        Timer::after(1000, function () {
            $this->exec();
            $this->timer();
        });
    }

    public function exec()
    {
        $this->git("/www/loan-app-api/");
        $this->git("/www/loan-app-test/");
        $this->git("/www/loan-app-admin/");
        $this->git("/www/apply-test/");
    }

    protected function git($dir)
    {
        $output = [];
        $return_var = 0;
        exec("git --git-dir=$dir/.git --work-tree=$dir pull", $output, $return_var);
        echo date('Y-m-d H:i:s');
        echo "dir:$dir  status:$return_var\n";
        echo implode("\n", $output), "\n";

    }

}

$cls = new CI();
$cls->start();

<?php
/**
 * @author Dawnc
 * @date   2020-05-10
 */

namespace Wms\Fw;


class Hook
{

    private array $callbacks = [];

    /**
     * @param string   $name 名称
     * @param callable $callback
     * @param int      $seq  按升序
     * @param array    $parameter
     */
    public function add(string $name, callable $callback, int $seq = 10, array $parameter = []): void
    {

        if (!isset($this->callbacks[$name])) {
            $this->callbacks[$name] = [];
        }

        $this->callbacks[$name][] = [
            "callback" => $callback,
            "seq" => $seq,
            "parameter" => $parameter,
        ];

        usort($this->callbacks[$name], function ($a, $b) {
            return $a['seq'] <=> $b['seq'];
        });


    }


    /**
     * @param string $name
     * @param array  $parameter
     * @return void
     */
    public function handle(string $name, array $parameter = []): void
    {
        $callbacks = $this->callbacks[$name] ?? [];

        foreach ($callbacks as $k => $c) {
            //执行
            call_user_func_array($c['callback'], array_merge($c['parameter'], $parameter));
        }
    }
}

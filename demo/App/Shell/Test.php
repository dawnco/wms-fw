<?php
/**
 * @author Dawnc
 * @date   2021-01-10
 */

namespace App\Shell;


use Wms\Lib\ShellHandle;

class Test extends ShellHandle
{
    protected function handle($param = null)
    {
        var_dump($param);
    }
}

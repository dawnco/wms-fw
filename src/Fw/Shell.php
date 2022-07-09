<?php

declare(strict_types=1);

/**
 * @author Dawnc
 * @date   2022-07-04
 */

namespace Wms\Fw;

abstract class Shell
{

    abstract protected function handle(array|null $param = null): void;

    protected function line(string $msg): void
    {
        echo $msg . "\n";
    }

}

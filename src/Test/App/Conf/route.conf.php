<?php

declare(strict_types=1);

/**
 * @author Dawnc
 * @date   2022-07-03
 */

return [
    "x1" => [\Wms\Test\App\Control\PortalControl::class, "index"],
    "x2" => [\Wms\Test\App\Control\PortalControl::class, "index", []],
    "x3/(\d+)" => [\Wms\Test\App\Control\PortalControl::class, "index", ['a', 'b']],
];

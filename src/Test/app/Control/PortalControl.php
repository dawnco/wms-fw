<?php

declare(strict_types=1);

/**
 * @author Dawnc
 * @date   2022-07-04
 */

namespace Wms\Test\App\Control;

use Wms\Exception\WmsException;

class PortalControl
{
    public function index($p1 = '', $p2 = '', $p3 = '')
    {
        throw new WmsException("xx");
        return [];
    }
}
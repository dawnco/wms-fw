<?php

declare(strict_types=1);

/**
 * @author Dawnc
 * @date   2022-07-04
 */

namespace Wms\Test\App\Control;

use Wms\Fw\Response;

class PortalControl
{
    public function index($p1 = '', $p2 = '', $p3 = '')
    {
        //throw new WmsException("xx");
        //return (new Response())->withContent("xxxx");
        return ["1x" => 'as'];
    }
}

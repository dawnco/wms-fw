<?php

declare(strict_types=1);

/**
 * @author Dawnc
 * @date   2022-07-09
 */

namespace Wms\Exception;

use Throwable;
use Wms\Fw\Response;

class ExceptionHandler
{

    /**
     * @param Throwable $throwable
     * @param Response  $response
     * @return Response
     */
    public function handle(Throwable $throwable, Response $response): Response
    {
        return $response->withBody(json_encode(['code' => $throwable->getCode()]));
    }
}

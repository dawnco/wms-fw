<?php
/**
 * @author Dawnc
 * @date   2020-12-21
 */

namespace App\Exception;


use Wms\Fw\WmsException;

class AppException extends WmsException
{

    public function __construct($message, $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}

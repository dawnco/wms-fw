<?php
/**
 * @author Dawnc
 * @date   2020-12-21
 */

namespace App\exception;


use Wms\Fw\Exception;

class AppException extends Exception
{

    public function __construct($message, $code = -1, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}

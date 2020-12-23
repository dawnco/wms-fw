<?php
/**
 * @author Dawnc
 * @date   2020-12-21
 */

namespace app\exception;


use wms\fw\Exception;

class AppException extends Exception
{

    public function __construct($message, $code = -1, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}

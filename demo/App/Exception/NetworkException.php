<?php
/**
 * @author Dawnc
 * @date   2020-12-21
 */

namespace App\exception;


class NetworkException extends AppException
{
    public function __construct($message = '', $code = -2, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

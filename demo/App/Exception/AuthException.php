<?php
/**
 * @author Dawnc
 * @date   2020-12-21
 */

namespace App\exception;


class AuthException extends AppException
{
    public function __construct($message = '', $code = 401, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

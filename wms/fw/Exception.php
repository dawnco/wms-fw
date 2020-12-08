<?php
/**
 * @author Dawnc
 * @date   2020-05-09
 */

namespace wms\fw;


use app\dict\ErrorCode;
use app\lib\SysErrorLog;
use wms\app\dict\ErrorMessage;
use wms\lib\WmsMQ;

class Exception extends \Exception
{


    public function __construct($message, $code = -1, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


}

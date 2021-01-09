<?php
/**
 * @author Dawnc
 * @date   2020-05-09
 */

namespace Wms\Fw;

use Wms\Lib\Log;

class WmsException extends \Exception
{

    protected $data = null;

    public function __construct($message, $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);


    }

    final public function setData($data)
    {
        $this->data = $data;
    }

    final public function getData()
    {
        return $this->data;
    }
}

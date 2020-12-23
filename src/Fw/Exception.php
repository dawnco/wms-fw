<?php
/**
 * @author Dawnc
 * @date   2020-05-09
 */

namespace Wms\Fw;

use Wms\Lib\Log;

class Exception extends \Exception
{

    protected $data = null;

    public function __construct($message, $code = -1, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        Log::info("Exception Code:%s Message: %s",
            $this->getCode(),
            $this->getMessage());

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

<?php
/**
 * @author Dawnc
 * @date   2021-01-10
 */

namespace App\Control;


use App\Exception\AuthException;
use App\Lib\Token;
use Wms\Fw\Db;
use Wms\Fw\Request;
use Wms\Lib\Redis;

abstract class RestFullControl extends Control
{

    public function restFull($id = 0)
    {
        $method = $this->method;
        switch ($method) {
            case "GET":
                if ($id) {
                    return $this->show($id);
                } else {
                    return $this->index();
                }
            break;
            case "POST":
                return $this->store();
            break;
            case "PUT":
                return $this->update($id);
            break;
            case "DELETE":
                return $this->destroy($id);
            break;
        }
    }

    abstract function index();

    abstract function show($id);

    abstract function store();

    abstract function update($id);

    abstract function destroy($id);

}

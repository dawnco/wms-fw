<?php
/**
 * @author Dawnc
 * @date   2020-12-24
 */

namespace App\Control;


use App\Exception\AuthException;
use App\Lib\Token;
use Wms\Fw\Db;
use Wms\Fw\Request;
use Wms\Lib\Redis;

class Control
{

    /**
     * @var Token
     */
    protected $token;


    /**
     * @var \wms\database\Mysqli
     */
    protected $db;

    protected $adminId = 0;

    /**
     * @var Request
     */
    protected $request;


    public function __construct($request)
    {

        $this->request = $request;
        $this->method  = $request->getMethod();
        $this->db      = Db::instance();
        $this->token   = new Token(Redis::getInstance(), $request->header("x-token"));
        $id            = $this->token->get('id');
        $this->adminId = $id;

        $this->init();

        if (!$id) {
            throw new AuthException("没有权限");
        }

    }

    public function init()
    {

    }

}

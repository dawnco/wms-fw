<?php
/**
 * @author Dawnc
 * @date   2020-12-24
 */

namespace App\Control;


use App\Exception\AuthException;
use App\Lib\Token;
use App\Model\Model;
use Wms\Fw\Db;
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

    private $method = "GET";

    public function __construct()
    {
        $this->method  = $GLOBALS['REQUEST_METHOD'] ?? 'GET';
        $this->db      = Db::instance();
        $token         = $_SERVER['HTTP_X_TOKEN'] ?? '';
        $this->token   = new Token(Redis::getInstance(), $token);
        $id            = $this->token->get('id');
        $this->adminId = $id;

        if (!$id) {
            throw new AuthException("没有权限");
        }

    }

    public function index($table = null, $id = 0)
    {

    }


}

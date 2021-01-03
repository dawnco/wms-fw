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

    private $method = "GET";

    public function __construct()
    {
        $this->method = $GLOBALS['REQUEST_METHOD'] ?? 'GET';
        $this->db     = Db::instance();
        $token        = $_SERVER['HTTP_X_TOKEN'] ?? '';
        $this->token  = new Token(Redis::getInstance(), $token);
        $id           = $this->token->get('id');
        if (!$id) {
            throw new AuthException();
        }

    }

    public function index($table, $id = 0)
    {

    }


}

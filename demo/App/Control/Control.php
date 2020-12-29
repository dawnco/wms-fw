<?php
/**
 * @author Dawnc
 * @date   2020-12-24
 */

namespace App\Control;


use App\Lib\Token;
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
        $this->token = new Token(Redis::getInstance(), $_SERVER['X-TOKEN'] ?? '');

    }

    public function index()
    {
        switch ($this->method) {
            case "GET":
                return $this->find();
            break;
            case "POST":
                return $this->post();
            break;
            case "PUT":
                return $this->update($this->filterField($_POST), $id);
            break;
            case "DELETE":
                return $this->delete($id);
            break;
        }
    }


    public function find()
    {
        $where[]   = ['AND id = ?', $id, true];
        $sql_where = $this->db->where($where);
        return $this->db->getLine(sprintf("SELECT %s FROM `%s` WHERE id = ?", $field, $this->table), [$id]);
    }

}

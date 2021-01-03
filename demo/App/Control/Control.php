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
        $token        = $_SERVER['X-TOKEN'] ?? '';
        $this->token = new Token(Redis::getInstance(), $token);

    }

    public function index()
    {
        $id = input("id");
        switch ($this->method) {
            case "GET":
                return $this->find();
            break;
            case "POST":
                return $this->post();
            break;
            case "PUT":
                return $this->update($this->filterField(input()), $id);
            break;
            case "DELETE":
                return $this->delete($id);
            break;
        }
    }


    public function find($id)
    {
        $where[]   = ['AND id = ?', $id, true];
        $sql_where = $this->db->where($where);
        return $this->db->getLine(sprintf("SELECT %s FROM `%s` WHERE id = ?", $field, $this->table), [$id]);
    }

}

<?php
/**
 * @author Dawnc
 * @date   2020-12-26
 */

namespace App\Model;


use Wms\Fw\Db;

class Model
{
    protected $table = '';

    // 排序的字段 没有留空
    protected $sortField = '';
    // 字段 留空从数据库获取
    protected $fields = [];

    protected $updatedAt = 'updated';
    protected $createdAt = 'created';


    protected $db = null;

    public function __construct($conf = 'default')
    {
        $this->db = Db::instance($conf);
    }

    public function table($table)
    {
        $this->table = $table;
    }

    public static function get($table = null, $conf = 'default')
    {
        $cls = new self($conf);
        if ($table) {
            $cls->table($table);
        }
        return $cls;
    }

    public function count($where = [])
    {
        $sql_where = $this->where($where);
        return $this->getVar("SELECT count(*) FROM `$this->table` WHERE " . $sql_where);
    }

    public function all($where = [], $page = 1, $size = 10, $order = "", $fields = '*')
    {

        if (empty($order)) {
            $order = 'id DESC';
        }

        $data = $this->db->getPageData($this->table, $where, $page, $size, $order, $fields);
        return $this->afterAll($data);
    }

    protected function afterAll($data)
    {
        return $data;
    }

    public function find($id)
    {
        $where[]   = ['AND id = ?', $id, true];
        $sql_where = $this->where($where);
        return $this->db->getLine('SELECT * FROM `' . $this->table . '` WHERE ' . $sql_where);
    }

    public function update($id, $data)
    {
        $data[$this->updatedAt] = $this->timestamp();

        $data = $this->filterData($data);

        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function create($data)
    {
        $data[$this->createdAt]    = $this->timestamp();
        $data[$this->enabledField] = Enabled::NO;

        $data = $this->filterData($data);

        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }


    protected function timestamp()
    {
        return date('Y-m-d H:i:s');
    }

    protected function where($where = [])
    {
        //$where[]   = ["AND `$this->enabledField` = " . Enabled::YES, true];
        $sql_where = $this->db->where($where);
        return $sql_where;
    }

    /**
     * 是否存在字段
     * @param $field
     * @return bool
     */
    public function hasField($field)
    {

        if (!$this->fields) {
            // 到数据库查询
            $data         = $this->db->getData("DESC `$this->table`");
            $this->fields = array_column($data, 'Field');
        }

        return in_array($field, $this->fields);

    }

    /**
     * 保留字段里的key
     * @param $data
     * @return array
     */
    protected function filterData($data)
    {
        $_d = [];
        foreach ($data as $k => $v) {
            if ($this->hasField($k)) {
                $_d[$k] = $v;
            }
        }

        return $_d;
    }

}

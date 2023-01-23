<?php
/**
 * @author Dawnc
 * @date   2020-05-09
 */

namespace Wms\Database;

use Wms\Lib\Log;

class Mysqli extends Database implements IDatabase
{
    /**
     * @var \mysqli
     */
    private $link = null;
    public $error = [];
    public $sql = null;

    protected $conf = [];

    public function __construct($conf)
    {
        $this->conf = $conf;
        $this->link = new \mysqli();
        $this->connect();
    }

    protected function connect()
    {
        $hostname = $this->conf['hostname'];
        $port = $this->conf['port'];
        $username = $this->conf['username'];
        $password = $this->conf['password'];
        $database = $this->conf['database'];
        $charset = $this->conf['charset'];

        // 结果int 不转为 string
        $this->link->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
        $isConnect = $this->link->real_connect($hostname, $username, $password, $database, $port);
        if (!$isConnect) {
            throw new DatabaseException("database connect error: $hostname $database");
        }
        $this->link->set_charset($charset);

        if (isset($this->conf['timezone'])) {
            $this->link->query(sprintf("SET time_zone '%s'", $this->conf['timezone']));
        }
    }

    /**
     * 获取一行数据
     * @param string     $query
     * @param array|null $bind
     * @return array
     */
    public function getLine($query, $bind = null)
    {

        $query = $this->prepare($query, $bind);
        $result = $this->exec($query);

        if (!$result) {
            return null;
        }
        $row = $result->fetch_assoc();
        $result->free();

        return $row ?: null;
    }

    /**
     * 快捷查询
     * @param string $table
     * @param string $value
     * @param string $index
     * @param string $field
     */
    public function getLineBy($table, $value, $index = "id", $field = " * ")
    {
        $query = "SELECT $field FROM `$table` WHERE `$index` = ?s ";
        return $this->getLine($this->prepare($query, array($value)));
    }


    /**
     * @param string $table
     * @param array  $where
     * @param string $field
     * @param string $order
     * @return false|mixed
     */
    public function getLineByWhere($table, $where, $field = " * ", $order = 'id DESC')
    {
        $rows = $this->getDataByWhere($table, $where, $field = " * ", $order, "LIMIT 1");
        return $rows ? $rows[0] : false;
    }


    /**
     * @param string $table
     * @param array  $where
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return array
     */
    public function getDataByWhere($table, $where, $field = " * ", $order = 'id DESC', $limit = '')
    {
        $where_sql = $this->where($where);
        $query = "SELECT $field FROM `$table` WHERE $where_sql ORDER BY $order $limit";
        return $this->getData($query);
    }

    /**
     * 获取一个值
     * @param string $query
     * @param mixed  $bind
     * @return mixed
     */
    public function getVar($query, $bind = null)
    {
        $query = $this->prepare($query, $bind);
        $line = $this->getLine($query);
        return $line ? array_shift($line) : false;
    }

    /**
     * 获取数据
     * @param string $query
     * @param array  $bind
     * @return array
     */
    public function getData($query, $bind = null)
    {
        $data = [];

        $query = $this->prepare($query, $bind);
        $result = $this->exec($query, $this->link);
        if (!$result) {
            return $data;
        }

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $result->free();

        return $data;
    }

    /**
     * 插入sql
     * @param string $table
     * @param array  $data
     * @return mixed
     */
    public function insert($table, $data)
    {
        $insert_fields = array();
        $insert_data = array();
        foreach ($data as $field => $value) {
            $insert_fields[] = "`{$field}`";
            $insert_data[] = '"' . $this->escape($value) . '"';
        }
        $insert_fields = implode(', ', $insert_fields);
        $insert_data = implode(', ', $insert_data);
        $query = "INSERT INTO `{$table}` ({$insert_fields}) values({$insert_data});";
        $result = $this->exec($query);

        if ($result) {
            return $this->link->insert_id;
        }

        return $result;
    }

    /**
     * 更新或者添加一条数据
     * @param string $table
     * @param array  $data
     * @param mixed  $value
     * @param string $field
     * @return mixed
     */
    public function upsert($table, $data, $value, $field = "id")
    {
        if ($value && $this->getVar("SELECT id FROM `$table` WHERE `$field` = ?s", [$value])) {
            return $this->update($table, $data, array($field => $value));
        } else {
            return $this->insert($table, $data);
        }
    }

    /**
     * 批量插入
     * @param string $table
     * @param array  $data
     * @return mixed
     */
    public function insertBatch($table, $data)
    {
        $insert_fields = array();
        foreach ($data as $value) {
            foreach ($value as $field => $row) {
                $insert_fields[] = "`{$field}`";
            }
            break;
        }
        $insert_fields = implode(', ', $insert_fields);


        foreach ($data as $field => $value) {
            $insert_data = array();
            foreach ($value as $row) {
                $insert_data[] = '"' . $this->escape($row) . '"';
            }
            $insert_data_str[] = "(" . implode(', ', $insert_data) . ")";
        }

        $query = "INSERT INTO `{$table}` ({$insert_fields}) values " . implode(",", $insert_data_str) . ";";
        $result = $this->exec($query);
        return $result;
    }

    /**
     * 批量插入(忽略重复索引)
     * @param string $table
     * @param array  $data
     * @return mixed
     */
    public function insertIgnoreBatch($table, $data)
    {
        $insert_fields = array();
        foreach ($data as $value) {
            foreach ($value as $field => $row) {
                $insert_fields[] = "`{$field}`";
            }
            break;
        }
        $insert_fields = implode(', ', $insert_fields);


        foreach ($data as $field => $value) {
            $insert_data = array();
            foreach ($value as $row) {
                $insert_data[] = '"' . $this->escape($row) . '"';
            }
            $insert_data_str[] = "(" . implode(', ', $insert_data) . ")";
        }

        $query = "INSERT IGNORE INTO `{$table}` ({$insert_fields}) values " . implode(",", $insert_data_str) . ";";
        $result = $this->exec($query);
        return $result;
    }

    /**
     * 简单分页数据
     * @param string $table
     * @param array  $where
     * @param int    $page
     * @param int    $size
     * @param string $order
     * @param string $fields
     * @return array
     */
    public function getPageData($table, $where = [], $page = 1, $size = 10, $order = "id DESC", $fields = '*')
    {

        $sql_where = $this->where($where);
        $total = $this->getVar("SELECT count(*) FROM `$table` WHERE " . $sql_where);

        $size = abs($size) ?: 1;
        $start = abs(($page ?: 1) - 1) * $size;

        $data['total'] = (int)$total;
        $data['page'] = (int)$page;

        $entries = [];

        $query = "SELECT {$fields} FROM `$table` WHERE $sql_where ORDER BY $order LIMIT $start, $size";
        $result = $this->exec($query, $this->link);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            $result->free();
        }

        $data['data'] = $entries;

        return $data;

    }

    /**
     * 简单分页数据
     * @param string $table
     * @param string $join
     * @param array  $where
     * @param int    $page
     * @param int    $size
     * @param string $order
     * @param string $fields
     * @return array
     */
    public function getJoinPageData(
        $table,
        $join = '',
        $where = [],
        $page = 1,
        $size = 10,
        $order = "id DESC",
        $fields = '*'
    ) {

        $sql_where = $this->where($where);
        $total = $this->getVar("SELECT count(a . id) FROM $table $join WHERE " . $sql_where);

        $size = abs($size) ?: 1;
        $start = abs(($page ?: 1) - 1) * $size;

        $data['total'] = (int)$total;
        $data['page'] = (int)$page;

        $entries = [];

        $query = "SELECT {
                $fields} FROM $table $join WHERE $sql_where ORDER BY $order LIMIT $start, $size";
        $result = $this->exec($query, $this->link);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $entries[] = $row;
            }
            $result->free();
        }

        $data['data'] = $entries;

        return $data;

    }

    /**
     * /**
     * 更新sql
     * @param string    $table
     * @param array     $data
     * @param array|int $where
     * @return bool
     */
    public function update($table, $data, $where)
    {
        $update_data = array();
        $update_where = array();
        foreach ($data as $field => $value) {
            $update_data[] = sprintf('`%s` = "%s"', $field, $this->escape($value));
        }
        $update_data = implode(', ', $update_data);

        if (is_array($where)) {
            foreach ($where as $field => $value) {
                $update_where[] = sprintf('`%s` = "%s"', $field, $this->escape($value));
            }
            $update_where = 'WHERE ' . implode(' AND ', $update_where);
        } elseif (is_numeric($where)) {
            $update_where = 'WHERE ' . $this->prepare("id = ?i", $where);
        } else {
            throw new DatabaseException("Db Not Specified Where", 500);
        }
        $query = "UPDATE `{$table}` SET {$update_data} {$update_where}";

        return $this->exec($query);
    }

    /**
     * @param string           $table
     * @param array|int|string $where
     * @return bool
     */
    public function delete($table, $where)
    {

        if (is_array($where)) {
            $delete_where = array();
            foreach ($where as $field => $value) {
                $delete_where[] = sprintf('`%s` = "%s"', $field, $this->escape($value));
            }
            $delete_where = 'WHERE ' . implode(' AND ', $delete_where);
        } elseif (is_numeric($where)) {
            $delete_where = 'WHERE ' . $this->prepare("id = ?i", $where);
        } else {
            throw new DatabaseException("Db Not Specified Where", 500);
        }

        $query = "DELETE FROM `$table` $delete_where";
        return $this->exec($query);
    }

    /**
     * 执行sql
     * @param string $query
     * @return boolean
     */
    private function exec($query)
    {
        $start = microtime(true);
        $result = $this->link->query($query);
        if ($result === false) {
            $error = sprintf(" %s : %s [%s]", $this->link->errno, $this->link->error, $query);
            throw new DatabaseException($error);
        }
        $end = microtime(true);
        $this->sql[] = "[" . substr(($end - $start) * 1000, 0, 5) . "ms] " . $query;
        return $result;
    }

    /**
     * 执行sql
     * @param string     $query
     * @param array|null $bind
     * @return bool
     */
    public function query($query, $bind = null)
    {
        $query = $this->prepare($query, $bind);
        $result = $this->exec($query);
        return $result;
    }

    /**
     * 转义安全字符
     * @param string $val
     * @return string
     */
    public function escape($val)
    {
        return $this->link->real_escape_string((string)$val);
    }

    /**
     * 关闭数据库
     */
    public function close()
    {
        return $this->link->close();
    }

    /**
     * 开启事物
     * @return bool
     */
    public function begin()
    {
        $this->sql[] = "begin";
        return $this->link->autocommit(false);
    }

    /**
     * 提交事物
     * @return bool
     */
    public function commit()
    {
        $this->sql[] = "commit";
        $result = $this->link->commit();
        $this->link->autocommit(true);
        return $result;
    }

    /**
     * 回滚
     * @return bool
     */
    public function rollback()
    {
        $this->sql[] = "rollback";
        $result = $this->link->rollback();
        return $result;
    }

    public function debug()
    {
        return $this->sql;
    }

    public function cleanDebug()
    {
        $this->sql = [];
    }

    public function ping()
    {
        try {
            $val = $this->link->ping();
            if (!$val) {
                Log::error("mysqli ping fail");
                $this->connect();
                Log::info("mysqli reconnect ok");
                return;
            }
        } catch (\Throwable $e) {
            Log::error("mysqli ping exception %s", $e);
            $this->connect();
            Log::info("mysqli reconnect ok");
        }
    }
}

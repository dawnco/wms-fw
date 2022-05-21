<?php
/**
 * @author Dawnco
 * @date   2022-05-19
 */

namespace Wms\Database\WDb;

use PDO;
use Wms\Database\DatabaseException;

class Connection
{

    const DB_ERROR_CODE = 50005;

    protected PDO $dbh;

    public function __construct(
        string $host,
        string $dbname,
        ?string $user = null,
        ?string $password = null,
        ?string $charset = 'utf8mb4',
        ?array $options = null
    ) {
        $this->dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $password, $options);

        $this->dbh->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbh->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
        $this->dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

    }

    /**
     * 执行一条非select查询,返回影响的行数
     * @param string $query
     * @param array  $params
     * @return int
     * @throws DatabaseException
     */
    public function execute(string $query, array $params = []): int
    {
        $stm = $this->statement($query, $params);
        return $stm->rowCount();
    }

    /**
     *  插入一条记录
     * @param string $table 表
     * @param array  $data  数据
     * @return int 上次插入的ID
     * @throws DatabaseException
     */
    public function insert(string $table, array $data): int
    {
        $fields = array_keys($data);
        $values = array_values($data);
        $fieldsStr = implode("`,`", $fields);
        $holders = implode(',', array_fill(0, count($fields), '?'));
        $query = "INSERT INTO `{$table}` (`{$fieldsStr}`) VALUE ({$holders})";

        $this->statement($query, $values);
        return (int)$this->dbh->lastInsertId();
    }

    /**
     * 批量插入
     * @param string $table
     * @param array  $data
     * @return void
     * @throws DatabaseException
     */
    public function insertBatch(string $table, array $data = [])
    {
        $fields = array_keys($data[0]);
        $fieldsStr = implode("`,`", $fields);
        $holdersOne = implode(',', array_fill(0, count($fields), '?'));

        $holders = "";
        $values = [];
        foreach ($data as $v) {
            $holders .= "(" . $holdersOne . "),";
            foreach ($v as $lv) {
                $values [] = $lv;
            }
        }

        $holders = rtrim($holders, ",");

        $query = "INSERT INTO `{$table}` (`{$fieldsStr}`) VALUES {$holders}";
        $this->statement($query, $values);
    }

    /**
     * 删除一条记录
     * @param string $table 表
     * @param array  $data  数据 ['id'=>1, 'pid'=>1]
     * @return int 上次插入的ID
     * @throws DatabaseException
     */
    public function delete(string $table, array $where): int
    {
        $values = [];
        $s = [];
        foreach ($where as $k => $v) {
            $s[] = " `$k` = ? ";
            $values[] = $v;
        }
        $values = array_values($where);
        $query = "DELETE FROM `{$table}` WHERE " . implode("AND", $s);
        $sth = $this->statement($query, $values);
        return $sth->rowCount();
    }


    /**
     * 更新记录
     * @param string $table 表
     * @param array  $data  数据
     * @param array  $where 条件 例如 ['id'=>1]
     * @return int 影响的行数
     * @throws DatabaseException
     */
    public function update(string $table, array $data, array $where): int
    {

        $params = [];
        $updateSets = [];
        $whereSets = [];
        foreach ($data as $field => $value) {
            $updateSets[] = sprintf('`%s` = ?', $field);
            $params[] = $value;
        }

        foreach ($where as $field => $value) {
            $whereSets[] = sprintf('`%s` = ?', $field);
            $params[] = $value;
        }

        $updateSetsStr = implode(",", $updateSets);
        $whereSetsStr = implode(" AND ", $whereSets);

        $query = "UPDATE `{$table}` SET $updateSetsStr WHERE 1 AND $whereSetsStr";

        $sth = $this->statement($query, $params);
        return $sth->rowCount();
    }

    /**
     * 获取一条记录
     * @param string $query     sql语句
     * @param array  $params    绑定值
     * @param string $className 结果对象
     * @return \stdClass
     * @throws DatabaseException
     */
    public function getLine(string $query, array $params = [], string $className = 'stdClass')
    {
        $sth = $this->statement($query, $params);
        $sth->setFetchMode(PDO::FETCH_CLASS, $className);
        return $sth->fetch(PDO::FETCH_CLASS);
    }

    /**
     * 获取结果
     * @param string $query     sql语句
     * @param array  $params    绑定值
     * @param string $className 结果映射类
     * @return array
     * @throws DatabaseException
     */
    public function getData(string $query, array $params = [], string $className = 'stdClass')
    {
        $sth = $this->statement($query, $params);
        return $sth->fetchAll(PDO::FETCH_CLASS, $className);
    }

    /**
     * 获取一个值
     * @param string $query  sql语句
     * @param array  $params 绑定值
     * @return mixed
     * @throws DatabaseException
     */
    public function getVar(string $query, array $params = [])
    {
        $sth = $this->statement($query, $params);
        return $sth->fetchColumn();
    }

    /**
     * @param string $query
     * @param array  $params
     * @return \PDOStatement
     * @throws DatabaseException
     */
    public function statement(string $query, array $params)
    {
        try {
            $sth = $this->dbh->prepare($query);
            $result = $sth->execute($params);
            if (!$result) {
                $msg = sprintf("SQL ERROR  %s [ %s : %s ]", $e->getMessage(), $query, json_encode($params));
                throw new DatabaseException($msg, self::DB_ERROR_CODE);
            }
            return $sth;
        } catch (\PDOException $e) {
            $msg = sprintf("SQL ERROR  %s [ %s : %s ]", $e->getMessage(), $query, json_encode($params));
            throw new DatabaseException($msg, self::DB_ERROR_CODE, $e);
        }
    }

    public function begin(): bool
    {
        return $this->dbh->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->dbh->commit();
    }

    public function rollback(): bool
    {
        return $this->dbh->rollBack();
    }

}

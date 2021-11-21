<?php

declare(strict_types=1);

/**
 * @author Hi Developer
 * @date   2021-09-18
 */

namespace Wms\Helper;

use Wms\Fw\Db as WmsDb;

class Db
{
    public static function getData(string $sql, array $bind = []): array
    {
        return static::getDb()->getData($sql, $bind);
    }

    public static function getLine(string $sql, array $bind = []): array
    {
        return static::getDb()->getLine($sql, $bind) ?? [];
    }

    public static function getVar(string $sql, array $bind = [])
    {
        $ret = static::getDb()->getLine($sql, $bind);
        return $ret ? array_shift($ret) : null;
    }

    /**
     * @param string $table table name
     * @param array  $data  ['field1'=>'value1', 'field2'=>'value2']
     * @param array  $key   ['field1', 'field1']
     * @param string $connectionName
     */
    public static function upsert(string $table, array $data, array $key)
    {

        $whereSql = [];
        $where    = [];
        foreach ($key as $v) {
            $where[$v]   = $data[$v];
            $whereSql[]  = "`$v` = ?";
            $whereBind[] = $data[$v];
        }

        $query = "SELECT id FROM `$table` WHERE  " . implode(" AND ", $whereSql);

        $exist = static::getVar($query, $whereBind);
        if ($exist) {
            return [
                'id'     => $exist,
                'affect' => static::update($table, $data, $where),
            ];
        } else {
            return [
                'id' => static::insert($table, $data)
            ];
        }

    }

    public static function insert(string $table, array $data): int
    {
        return static::getDb()->insert($table, $data);
    }

    public static function insertBatch(string $table, array $data): int
    {
        return static::getDb()->insertBatch($table, $data);
    }

    public static function update(string $table, array $data, array $where): int
    {
        static::getDb()->update($table, $data, $where);
        return 1;
    }

    public static function delete(string $table, array $where): int
    {
        return static::getDb()->delete($table, $where);
    }

    public static function query(string $sql, array $bind = []): int
    {
        return static::getDb()->query($sql, $bind);
    }

    /**
     * @param string $table
     * @param array  $where [["sql", val, condition]] val为数组的时候  sql只需要一个占位符
     * @param int    $page
     * @param int    $size
     * @param string $order
     * @param string $fields
     */
    public static function getPageData(string $table, array $where, int $page = 1, int $size = 10, string $order = "id DESC", string $fields = '*')
    {

        $whereData = [];
        $bind      = [];
        foreach ($where as $factor) {

            $condition = $factor[2] ?? $factor[1];
            if ($condition) {
                if (is_array($factor[1])) {
                    foreach ($factor[1] as $tv) {
                        $bind[]   = $tv;
                        $holder[] = "?";
                    }
                    $whereData[] = str_replace("?", implode(",", $holder), $factor[0]);
                } else {
                    $whereData[] = $factor[0];
                    $bind[]      = $factor[1];
                }

            }
        }
        $whereSql = 'WHERE 1 ' . implode(' ', $whereData);

        $total = static::getVar("SELECT count(*) FROM  `$table` $whereSql", $bind);

        $size  = abs($size) ?: 1;
        $start = abs(($page ?: 1) - 1) * $size;

        $data['total'] = (int)$total;
        $data['page']  = (int)$page;

        $sql = "SELECT $fields FROM  `$table` $whereSql ORDER BY $order LIMIT $start, $size";

        return [
            'total' => $total,
            'page'  => $page,
            'data'  => static::getData($sql, $bind),
        ];

    }

    protected static function getDb()
    {
        return WmsDb::instance();
    }
}

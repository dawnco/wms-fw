<?php

declare(strict_types=1);

/**
 * @author Hi Developer
 * @date   2022-05-21
 */

namespace Wms\Database\WDb;

use Wms\Fw\Conf;

/**
 * @method static insert(string $table, array $data = []): int
 * @method static insertBatch(string $table, array $data = []): int
 * @method static delete(string $table, array $where = []): int
 * @method static update(string $table, array $data, array $where): int
 * @method static getLine(string $query, array $params = [], string $className = 'stdClass')
 * @method static getData(string $query, array $params = [], string $className = 'stdClass')
 * @method static getVar(string $query, array $params = [])
 * @method static execute(string $query, array $params = []): int
 */
class WDb
{

    protected static array $connections = [];

    /**
     * @param string $confName
     * @return Connection
     */
    public static function connection(string $confName = 'default'): Connection
    {
        if (!isset(self::$connections[$confName])) {
            $conf = Conf::get("app.db.$confName");
            self::$connections[$confName] = new Connection(
                $conf['hostname'],
                $conf['database'],
                $conf['username'],
                $conf['password'],
            );
        }
        return self::$connections[$confName];
    }

    /**
     * @param string $confName
     * @return void
     */
    public static function release(string $confName = 'default')
    {
        unset(self::$connections[$confName]);
    }

    public static function __callStatic($name, $arg)
    {
        $connection = self::connection();
        return $connection->{$name}(...$arg);
    }
}

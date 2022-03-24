<?php
/**
 * @author Dawnc
 * @date   2020-05-08
 */

namespace Wms\Fw;


use Wms\Database\Mysqli;

class Db
{

    private static $instance = [];

    /**
     * @param $conf
     * @return Mysqli
     */
    public static function instance($conf = 'default')
    {
        if (!isset(self::$instance[$conf])) {
            $option = Conf::get("app.db.$conf");
            $type = isset($option['driver']) ? $option['driver'] : "\\Wms\\Database\\Mysqli";
            self::$instance[$conf] = new $type($option);
        }
        return self::$instance[$conf];
    }

}

<?php
/**
 * @author Dawnc
 * @date   2020-12-08
 */

namespace app\control;


use wms\fw\Db;

class PortalControl
{
    public function index()
    {
        $db   = Db::instance();
        $data = $db->getData("SELECT * FROM sys_device limit 1");
        var_dump($data);
    }
}

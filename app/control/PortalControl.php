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
        $code = input("code");
        $data = $db->getData("SELECT code,`name`,price,`date` FROM product WHERE `code` = ? ORDER BY id DESC LIMIT 30", [$code]);
        return $data;
    }

    public function save()
    {
        $db = Db::instance();

        $code  = input("code");
        $name  = input("name");
        $price = input("price");

        $row = [
            'code'  => $code,
            'name'  => $name,
            'price' => $price,
            'date'  => date("Y-m-d"),
        ];

        $db->insert("product", $row);

    }
}
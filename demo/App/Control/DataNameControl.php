<?php
/**
 * @author Dawnc
 * @date   2021-01-10
 */

namespace App\Control;


class DataNameControl extends Control
{
    public function name()
    {
        $type = input('type');
        $id   = input('id');
        $name = $this->db->getVar("SELECT `name` FROM `$type` WHERE id = ?", [$id]);
        return $name ?: null;
    }
}

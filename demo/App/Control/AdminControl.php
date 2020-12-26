<?php
/**
 * @author Dawnc
 * @date   2020-08-05
 */

namespace App\Control;

use App\Dict\Dict;

class AdminControl extends Control
{

    protected $table = 'admin';

    protected function default()
    {

        $where[] = ["AND `enabled` = ? ", input('enabled')];
        $where[] = ["AND `username` LIKE ?l ", input('username')];
        $where[] = ["AND `mobile` LIKE ?l ", input('mobile')];
        $where[] = ["AND `name` LIKE ?l ", input('name')];

        $page   = input('page') ?: 1;
        $size   = input('pageSize') ?: 10;
        $order  = "id DESC";
        $fields = "id, enabled, name, username, groupId, mobile, updated, created";

        return $this->db->getPageData($this->table, $where, $page, $size, $order, $fields);
    }

    protected function store()
    {
        $row = input();

        $this->checkAuth();

        if ($this->db->getVar("SELECT id FROM admin WHERE username = ? ", [$row['username']])) {
            throw new Exception("账号已经存在");
        }

        if ($row['password']) {
            $row['password'] = password_hash($row['password'], PASSWORD_DEFAULT);
        }

        $this->db->insert('admin', $row);

    }

    protected function edit()
    {
        $id = input('id');
        return $this->db->getLineBy($this->table, $id, 'id', "id, name, username, mobile, groupId, updated, created");
    }

    protected function update()
    {
        $this->checkAuth();

        $id              = input('id');
        $password        = input('password');
        $row['username'] = input('username');
        $row['name']     = input('name');
        $row['mobile']   = input('mobile');
        $row['password'] = input('password');

        if ($id == 1 && Session::get('id') != 1) {
            throw new Exception('admin 账号才能修改');
        }

        if (!empty($password)) {
            $row['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->db->update('admin', $row, ['id' => $id]);
        return true;
    }

    protected function enabled()
    {

        $this->checkAuth();

        $id = input('id');

        $enabled = $this->db->getVar("SELECT enabled FROM $this->table WHERE id = ? ", [$id]);

        $set = $enabled == Dict::YES ? Dict::NO : Dict::YES;
        $this->db->update($this->table, ['enabled' => $set], ['id' => $id]);
        return $set;
    }

}

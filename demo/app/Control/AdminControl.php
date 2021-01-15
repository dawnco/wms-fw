<?php
/**
 * @date   2021-01-10 19:32:40
 */

namespace App\Control;

class AdminControl extends RestFullControl
{
    protected $table;

    public function init()
    {
        $this->table = "admin";
    }

    /**
     * 列表
     */
    function index()
    {
        $table   = $this->table;
        $fields  = "id,name,username,created,updated";
        $where[] = ["AND `id` = ?", $this->request->input('id')];

        $page = $this->request->input('page', 1);
        $size = $this->request->input('pageSize', 10);

        $sortField = $this->request->input('sortField', 'id');
        $sortOrder = $this->request->input('sortOrder', 'DESC');
        $order     = $sortField . " " . $sortOrder;

        $data = $this->db->getPageData($table, $where, $page, $size, $order, $fields);
        return $data;
    }

    /**
     * 显示数据
     */
    function show($id)
    {
        $fields   = "id,name,username";
        $table    = $this->table;
        $where[]  = ['AND id = ?', $id, true];
        $sqlWhere = $this->db->where($where);
        return $this->db->getLine('SELECT ' . $fields . ' FROM `' . $table . '` WHERE ' . $sqlWhere);
    }

    /**
     * 保存
     */
    function store()
    {
        $table = $this->table;

        $row["enabled"]  = $this->request->input("enabled");
        $row["username"] = $this->request->input("username");
        $row["name"]     = $this->request->input("name");
        $row["phone"]    = $this->request->input("phone");
        $row["password"] = $this->request->input("password");
        $row["mobile"]   = $this->request->input("mobile");
        $row["groupId"]  = $this->request->input("groupId");

        if ($row['password']) {
            $row['password'] = password_hash($row['password'], PASSWORD_DEFAULT);
        }


        return $this->db->insert($table, $row);
    }

    /**
     * 修改
     */
    function update($id)
    {
        $table = $this->table;

        $row["enabled"]  = $this->request->input("enabled");
        $row["username"] = $this->request->input("username");
        $row["name"]     = $this->request->input("name");
        $row["phone"]    = $this->request->input("phone");
        $row["password"] = $this->request->input("password");
        $row["mobile"]   = $this->request->input("mobile");
        $row["groupId"]  = $this->request->input("groupId");

        if ($row['password']) {
            $row['password'] = password_hash($row['password'], PASSWORD_DEFAULT);
        }

        return $this->db->update($table, $row, ['id' => $id]);
    }

    /**
     * 删除
     */
    function destroy($id)
    {
        $table = $this->table;
        return $this->db->delete($table, ['id' => $id]);
    }

}


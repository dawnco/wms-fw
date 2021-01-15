<?php
/**
 * @date   2021-01-10 19:04:16
 */

namespace App\Control;

class CustomerControl extends RestFullControl
{
    protected $table;

    public function init()
    {
        $this->table = "customer";
    }

    /**
     * 列表
     */
    function index()
    {
        $table   = $this->table;
        $fields  = "*";
        $where[] = ["AND `id` = ?", $this->request->input('id')];
        $where[] = ["AND `group` = ?", $this->request->input('group')];

        $keyword = $this->request->input('keyword');

        if ($keyword) {
            $where[] = ["AND (`name` LIKE ?l", $keyword];
            $where[] = ["OR `company` LIKE ?l", $keyword];
            $where[] = ["OR `mobile` LIKE ?l", $keyword];
            $where[] = ["OR `mobile2` LIKE ?l", $keyword];
            $where[] = ["OR `note` LIKE ?l)", $keyword];
        }

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
        $fields   = "*";
        $table    = $this->table;
        $where[]  = ['AND id = ?', $id, true];
        $sqlWhere = $this->db->where($where);
        return $this->db->getLine("SELECT $fields FROM `$table` WHERE " . $sqlWhere);
    }

    /**
     * 保存
     */
    function store()
    {
        $table = $this->table;

        $row["adminId"] = $this->adminId;

        $row["group"]   = $this->request->input("group");
        $row["name"]    = $this->request->input("name");
        $row["company"] = $this->request->input("company");
        $row["mobile"]  = $this->request->input("mobile");
        $row["mobile2"] = $this->request->input("mobile2");
        $row["area"]    = $this->request->input("area");
        $row["note"]    = $this->request->input("note");

        return $this->db->insert($table, $row);
    }

    /**
     * 修改
     */
    function update($id)
    {
        $table = $this->table;

        $row["group"]   = $this->request->input("group");
        $row["name"]    = $this->request->input("name");
        $row["company"] = $this->request->input("company");
        $row["mobile"]  = $this->request->input("mobile");
        $row["mobile2"] = $this->request->input("mobile2");
        $row["area"]    = $this->request->input("area");
        $row["note"]    = $this->request->input("note");

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


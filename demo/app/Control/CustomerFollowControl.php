<?php
/**
 * @date   2021-01-10 19:18:25
 */

namespace App\Control;

class CustomerFollowControl extends RestFullControl
{
    protected $table;

    public function init()
    {
        $this->table = "customer_follow";
    }

    /**
     * 列表
     */
    function index()
    {
        $table   = $this->table;
        $fields  = "*";
        $where[] = ["AND `id` = ?", $this->request->input('id')];
        $where[] = ["AND `customerId` = ?", $this->request->input('customerId')];

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

        $row["customerId"] = $this->request->input("customerId");
        $row["adminId"]    = $this->adminId;
        $row["note"]       = $this->request->input("note");


        return $this->db->insert($table, $row);
    }

    /**
     * 修改
     */
    function update($id)
    {
        $table = $this->table;

        $row["note"] = $this->request->input("note");

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


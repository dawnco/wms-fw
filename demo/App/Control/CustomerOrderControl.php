<?php
/**
 * @date   2021-01-10 19:47:59
 */

namespace App\Control;

class CustomerOrderControl extends RestFullControl
{
    protected $table;

    public function init()
    {
        $this->table = "customer_order";
    }

    /**
     * 列表
     */
    public function index()
    {
        $table   = $this->table;
        $fields  = "*";
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
    public function show($id)
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
    public function store()
    {
        $table = $this->table;

        $row["adminId"]          = $this->adminId;
        $row["customerId"]       = $this->request->input("customerId");
        $row["productName"]      = $this->request->input("productName");
        $row["productQuantity"]  = $this->request->input("productQuantity");
        $row["price"]            = $this->request->input("price");
        $row["cost1"]            = $this->request->input("cost1");
        $row["cost2"]            = $this->request->input("cost2");
        $row["cost3"]            = $this->request->input("cost3");
        $row["cost4"]            = $this->request->input("cost4");
        $row["expressConsignee"] = $this->request->input("expressConsignee");
        $row["expressCode"]      = $this->request->input("expressCode");
        $row["expressNumber"]    = $this->request->input("expressNumber");
        $row["note"]             = $this->request->input("note");


        return $this->db->insert($table, $row);
    }

    /**
     * 修改
     */
    public function update($id)
    {
        $table = $this->table;

        $row["customerId"]       = $this->request->input("customerId");
        $row["adminId"]          = $this->request->input("adminId");
        $row["productName"]      = $this->request->input("productName");
        $row["productQuantity"]  = $this->request->input("productQuantity");
        $row["price"]            = $this->request->input("price");
        $row["cost1"]            = $this->request->input("cost1");
        $row["cost2"]            = $this->request->input("cost2");
        $row["cost3"]            = $this->request->input("cost3");
        $row["cost4"]            = $this->request->input("cost4");
        $row["expressConsignee"] = $this->request->input("expressConsignee");
        $row["expressCode"]      = $this->request->input("expressCode");
        $row["expressNumber"]    = $this->request->input("expressNumber");
        $row["note"]             = $this->request->input("note");


        return $this->db->update($table, $row, ['id' => $id]);
    }

    /**
     * 删除
     */
    public function destroy($id)
    {

        $table = $this->table;
        return $this->db->delete($table, ['id' => $id]);
    }

}


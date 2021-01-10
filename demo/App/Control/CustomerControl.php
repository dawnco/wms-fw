<?php
/**
 * @author Dawnc
 * @date   2021-01-09
 */

namespace App\Control;


class CustomerControl extends RestFullControl
{
    protected $table;

    public function init()
    {
        $this->table = "customer";
    }

    public function follow()
    {
        $note       = input('note');
        $customerId = input('customerId');

        $this->db->insert('customer_follow', [
            'customerId' => $customerId,
            'adminId'    => $this->adminId,
            'note'       => $note,
        ]);

    }

    public function list()
    {
        $customerId = input('customerId');
        $data       = $this->db->getData("SELECT * FROM customer_follow WHERE customerId = ? ORDER BY id DESC", [$customerId]);
        return $data;
    }

    function index()
    {

        $table   = $this->table;
        $fields  = "*";
        $where[] = ["AND `name` = ?", $this->request->input('name')];
        $page    = $this->request->input('page', 1);
        $size    = $this->request->input('pageSize', 10);

        $sortField = $this->request->input('sortField', 'id');
        $sortOrder = $this->request->input('sortOrder', 'DESC');
        $order     = $sortField . " " . $sortOrder;

        $data = $this->db->getPageData($table, $where, $page, $size, $order, $fields);
        return $data;

    }

    function show($id)
    {
        $fields   = "*";
        $table    = $this->table;
        $where[]  = ['AND id = ?', $id, true];
        $sqlWhere = $this->db->where($where);
        return $this->db->getLine('SELECT ' . $fields . ' FROM `' . $table . '` WHERE ' . $sqlWhere);
    }

    function store()
    {
        $table = $this->table;
        $row   = [
            'name' => $this->request->input('name'),
        ];
        return $this->db->insert($table, $row);
    }

    function update($id)
    {
        $table = $this->table;
        $row   = [
            'name' => $this->request->input('name'),
        ];
        return $this->db->update($table, $row, ['id' => $id]);
    }

    function destroy($id)
    {
        $table = $this->table;
        return $this->db->delete($table, ['id' => $id]);
    }

}

<?php
/**
 * @author Dawnc
 * @date   2020-08-05
 */

namespace App\Control;

use App\Dict\Dict;

class UserControl extends SampleFullControl
{

    protected $table = 'user';

    public function enter($id = 0)
    {
        $this->index($this->table, $id);
    }

    public function find($id)
    {
        $where[]   = ['AND id = ?', $id, true];
        $sql_where = $this->where($where);
        return $this->db->getLine('SELECT id,username,`name` FROM `' . $this->table . '` WHERE ' . $sql_where);
    }

    public function update($id, $data)
    {
        $data[$this->updatedAt] = $this->timestamp();

        if ($data['password']) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $data = $this->filterData($data);

        return $this->db->update($this->table, $data, ['id' => $id]);
    }
}

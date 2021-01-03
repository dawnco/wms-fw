<?php
/**
 * @author Dawnc
 * @date   2020-08-05
 */

namespace App\Control;

use App\Dict\Dict;

class AdminControl extends TableControl
{

    protected $table = 'admin';

    public function enter($id = 0)
    {
        return $this->index($this->table, $id);
    }

    protected function find($model, $id)
    {
        return $model->find($id, "id,username,name");
    }

    protected function update($model, $id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $model->update($id, $data);
    }

    protected function create($model, $data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $model->create($data);
    }

}

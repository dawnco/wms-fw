<?php
/**
 * @author Dawnc
 * @date   2020-08-05
 */

namespace App\Control;

class AdminControl extends SampleFullControl
{

    protected $table = 'admin';

    public function enter($id = 0)
    {
        return $this->sample($this->table, $id);
    }

    public function show($id)
    {
        return $this->model->find($id, "id,username,name");
    }

    public function update($id)
    {
        $data = $this->request->data();
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->model->update($id, $data);
    }

    public function store()
    {
        $data             = $this->request->data();
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $model->create($data);
    }

}

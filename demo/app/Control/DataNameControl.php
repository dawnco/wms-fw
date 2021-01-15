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
        $type = $this->request->input('type');
        $id   = $this->request->input('id');

        switch ($type) {
            case 'admin':
                $name = $this->admin($id);
            break;
            case 'customer':
                $name = $this->customer($id);
            break;
            case 'customerCompany':
                $name = $this->customerCompany($id);
            break;
        }

        return $name ?: null;
    }

    protected function admin($id)
    {
        return $this->db->getVar("SELECT `name` FROM admin WHERE id = ?", [$id]);
    }

    protected function customer($id)
    {
        return $this->db->getVar("SELECT `name` FROM customer WHERE id = ?", [$id]);
    }

    protected function customerCompany($id)
    {
        return $this->db->getVar("SELECT `company` FROM customer WHERE id = ?", [$id]);
    }
}

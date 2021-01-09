<?php
/**
 * @author Dawnc
 * @date   2021-01-09
 */

namespace App\Control;


use Wms\Fw\WmsException;

class CustomerControl extends TableControl
{

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
        $data = $this->db->getData("SELECT * FROM customer_follow WHERE customerId = ? ORDER BY id DESC", [$customerId]);
        return $data;
    }

}

<?php
/**
 * @author Dawnc
 * @date   2021-01-09
 */

namespace App\Control;


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

}

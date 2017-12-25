<?php

namespace Codeages\Biz\Address\Dao\Impl;

use Codeages\Biz\Address\Dao\AddressDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class AddressDaoImpl extends GeneralDaoImpl implements AddressDao
{
    protected $table = 'biz_address';

    public function findByUserId($userId)
    {
        return $this->findByFields(array('user_id' => $userId));
    }

    public function removeDefaultAddress($userId, $addressId)
    {
        $sql = "UPDATE {$this->table} SET is_default = 0 WHERE user_id = ? AND id != ?";

        return $this->db()->executeUpdate($sql, array($userId, $addressId));
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created_time', 'updated_time'),
            'orderbys' => array(
                'id',
                'created_time',
                'is_default'
            ),
            'conditions' => array(
                'user_id = :user_id'
            )
        );
    }
}

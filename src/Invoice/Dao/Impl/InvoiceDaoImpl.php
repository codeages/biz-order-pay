<?php

namespace Codeages\Biz\Invoice\Dao\Impl;

use Codeages\Biz\Framework\Dao\DaoException;
use Codeages\Biz\Invoice\Dao\InvoiceDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class InvoiceDaoImpl extends GeneralDaoImpl implements InvoiceDao
{
    protected $table = 'invoice';

    public function declares()
    {
        return array(
            'orderbys' => array(
                'createdTime',
            ),
            'serializes' => array(
                'orderIds' => 'delimiter',
            ),
            'timestamps' => array(
                'createdTime',
                'updatedTime',
            ),
            'conditions' => array(
                'id = :id',
                'userId = :userId',
                'status = :status',
                'userId IN ( :userIds)',
                'sn = :sn',
            ),
        );

    }

    public function findByUserId($userId)
    {
        return $this->findByFields(array(
            'userId' => $userId
        ));
    }
}
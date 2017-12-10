<?php

namespace Codeages\Biz\Invoice\Dao\Impl;

use Codeages\Biz\Framework\Dao\DaoException;
use Codeages\Biz\Invoice\Dao\InvoiceTemplateDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class InvoiceTemplateDaoImpl extends GeneralDaoImpl implements InvoiceTemplateDao
{
    protected $table = 'invoice_template';

    public function declares()
    {
        return array(
            'orderbys' => array(
                'isDefault',
                'id',
                'createdTime',
            ),
            'timestamps' => array(
                'createdTime',
                'updatedTime',
            ),
            'conditions' => array(
                'id = :id',
                'userId = :userId',
            ),
        );
    }

    public function getByUserIdAndIsDefault($userId, $isDefault)
    {
        return $this->getByFields(array('userId' => $userId, 'isDefault' => $isDefault));
    }
}
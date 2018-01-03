<?php

namespace Codeages\Biz\Order\Dao\Impl;

use Codeages\Biz\Order\Dao\OrderAddressDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class OrderAddressDaoImpl extends GeneralDaoImpl implements OrderAddressDao
{
    protected $table = 'biz_order_address';

    public function getByOrderId($orderId)
    {
        return $this->getByFields(array(
            'order_id' => $orderId
        ));
    }

    public function findByOrderIds($orderIds)
    {
        return $this->findInField('order_id', $orderIds);
    }

    public function declares()
    {
        return array(
            'timestamps' => array('created_time', 'updated_time')
        );
    }
}

<?php

namespace Codeages\Biz\Order\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface OrderAddressDao extends GeneralDaoInterface
{
    public function getByOrderId($orderId);

    public function findByOrderIds($orderIds);
}

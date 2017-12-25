<?php

namespace Codeages\Biz\Order\Service;

interface OrderAddressService
{
    public function getOrderAddressByOrderId($orderId);

    public function createOrderAddress($orderAddres);

}

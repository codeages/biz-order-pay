<?php

namespace Codeages\Biz\Order\Service\Impl;

use AppBundle\Common\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;
use Codeages\Biz\Order\Service\OrderAddressService;
use Codeages\Biz\Order\Status\Order\PayingOrderStatus;
use Codeages\Biz\Order\Status\Order\CreatedOrderStatus;
use AppBundle\Common\Exception\InvalidArgumentException;

class OrderAddressServiceImpl extends BaseService implements OrderAddressService
{
    private $allowed_deducts_fields = array(
        'order_id', 'name', 'mobile', 'province', 'city', 'district', 'town', 'detail', 'zip'
    );

    public function createOrderAddress($orderAddress)
    {
        if (!ArrayToolkit::requireds($orderAddress, array(
            'order_id', 'name', 'mobile', 'province', 'detail'))) {
            throw new InvalidArgumentException('Invalid argument.');
        }

        $order = $this->getOrderService()->getOrder($orderAddress['order_id']);

        if (!in_array($order['status'], array(CreatedOrderStatus::NAME, PayingOrderStatus::NAME))) {
            throw new AccessDeniedException('Order status is invalid.');
        }

        $orderAddressFields = ArrayToolkit::parts($orderAddress, $this->allowed_deducts_fields);

        return $this->getOrderAddressDao()->create($orderAddressFields);
    }

    public function getOrderAddressByOrderId($orderId)
    {
        return $this->getOrderAddressDao()->getByOrderId($orderId);
    }

    protected function getOrderAddressDao()
    {
        return $this->biz->dao('Order:OrderAddressDao');
    }

    protected function getOrderService()
    {
        return $this->biz->service('Order:OrderService');
    }
}

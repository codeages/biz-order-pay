<?php

namespace Codeages\Biz\Order\Status\Order;

class ShippingOrderStatus extends AbstractOrderStatus
{
    const NAME = 'shipping';

    public function getName()
    {
        return self::NAME;
    }

    public function process($data = array())
    {
        $items = $this->getOrderItemDao()->findByOrderId($this->order['id']);
        foreach ($items as $item) {
            if (!in_array($item['shipping_type'], array('virtual', 'express'))) {
                throw new \Exception("order shipping type {$item['shipping_type']} is not found");
            }

            $method = 'make'.ucfirst($item['shipping_type']).'Order';
            $order  = $this->$method();
        }

        return $order;
    }

    private function makeVirtualOrder()
    {
        return $this->getOrderDao()->get($this->order['id']);
    }

    private function makeExpressOrder()
    {
        return $this->changeStatus(self::NAME);
    }

    public function refunding($data = array())
    {
        return $this->getOrderStatus(RefundingOrderStatus::NAME)->process($data);
    }

    public function finished($data = array())
    {
        return $this->getOrderStatus(FinishedOrderStatus::NAME)->process($data);
    }

    public function shipping($data = array())
    {
        return $this->process($data);
    }   
}
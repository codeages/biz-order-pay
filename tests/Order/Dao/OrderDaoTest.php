<?php

namespace Tests;

use Codeages\Biz\Order\Dao\OrderDao;
use Codeages\Biz\Order\Dao\OrderLogDao;
use Codeages\Biz\Order\Service\OrderService;
use Codeages\Biz\Order\Service\WorkflowService;

class OrderDaoTest extends IntegrationTestCase
{
    public function testQueryWithItemConditions()
    {
        $this->getOrderDao()->create(
            array(
                'id' => 100,
                'title' => 'order_title',
                'sn' => 'order_sn',
                'price_amount' => 0,
                'price_type' => 'abc',
                'pay_amount' => 0,
                'user_id' => 1,
            )
        );

        $this->getOrderItemDao()->create(
            array(
                'id' => 10101,
                'order_id' => 100,
                'title' => 'order_item_name',
                'sn' => 'order_item_sn',
                'price_amount' => 0,
                'price_type' => 'abc',
                'pay_amount' => 0,
                'user_id' => 1,
            )
        );

        $orderInfos = $this->getOrderdao()->queryWithItemConditions(
            array('order_item_title' => 'rder_item_n'),
            array('created_time' => 'desc'),
            0,
            1
        );

        $this->assertEquals('order_title', $orderInfos[0]['title']);
    }

    /**
     * @return OrderDao
     */
    protected function getOrderDao()
    {
        return $this->biz->dao('Order:OrderDao');
    }

    protected function getOrderItemDao()
    {
        return $this->biz->dao('Order:OrderItemDao');
    }
}

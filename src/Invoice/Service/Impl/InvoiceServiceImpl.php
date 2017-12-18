<?php

namespace Codeages\Biz\Invoice\Service\Impl;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;
use Codeages\Biz\Invoice\Service\InvoiceService;
use Codeages\Biz\Framework\Service\Exception\AccessDeniedException;


class InvoiceServiceImpl extends BaseService implements InvoiceService
{
    public function getInvoice($id)
    {
        return $this->getInvoiceDao()->get($id);
    }

    public function tryApplyInvoice($orderIds)
    {
        $orders = $this->getOrderService()->findOrdersByIds($orderIds);

        $user = $this->biz['user'];

        foreach ($orders as $key => $order) {
            if ($user['id'] != $order['user_id'] ) {
                throw new AccessDeniedException('order owner is invalid');
            }

            if (!empty($order['invoice_sn'])) {
                throw new AccessDeniedException('order invoiced');
            }
        }

        return $orders;
    }

    public function submitApply($apply)
    {
        $apply = $this->prepareApply($apply);

        $orders = $this->tryApplyInvoice($apply['orderIds']);
        $money = array_sum(ArrayToolkit::column($orders, 'pay_amount'));
        if ($apply['money'] != $money) {
            throw $this->createAccessDeniedException('申请金额和订单金额不符');
        }

        try {
            $this->biz['db']->beginTransaction();

            //update my invoice template
            if (!empty($apply['templateId'])) {
                $this->getInvoiceTemplateService()->updateInvoiceTemplate($apply['templateId'], $apply);
            } else {
                $this->getInvoiceTemplateService()->createInvoiceTemplate($apply);
            }

            $apply = $this->createInvoice($apply);

            foreach ($orders as $order) {
                $this->getOrderService()->updateOrderInvoiceSn($order['id'], $apply['sn']);
            }

            $this->biz['db']->commit();
        } catch (\Exception $e) {
            throw $e;
        }

        return $apply;
    }

    protected function prepareApply($apply)
    {
        $user = $this->biz['user'];
        $apply['user_id'] = $user['id'];

        $apply['orderIds'] = explode('|', $apply['orderIds']);

        $apply['money'] *= 100;

        $apply['sn'] = $this->generateSn();

        return $apply;
    }

    protected function generateSn()
    {
        return date('YmdHis', time()).mt_rand(10000, 99999);
    }

    public function createInvoice($apply)
    {
        if (!ArrayToolkit::requireds($apply, array('title', 'type', 'address', 'phone', 'email', 'receiver', 'money', 'sn'))) {
            throw $this->createInvalidArgumentException('Lack of required fields');
        }

        $apply = ArrayToolkit::parts($apply, array('title', 'type', 'taxpayer_identity', 'comment', 'address', 'phone', 'email', 'receiver', 'money', 'user_id', 'sn'));

        $apply['comment'] = $this->purifyHtml($apply['comment'], true);

        $apply = $this->getInvoiceDao()->create($apply);

        return $apply;
    }

    protected function purifyHtml($html, $trusted = false)
    {
        $htmlHelper = $this->biz['html_helper'];

        return $htmlHelper->purify($html, $trusted);
    }

    public function updateInvoice($id, $fields)
    {
        $fields = ArrayToolkit::filter($fields, array(
            'title' => '',
            'taxpayer_identity' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'receiver' => '',
            'status' => 'unchecked',
            'review_user_id' => 0,
            'number' => '',
            'post_number' => '',
            'review_comment' => ''
        ));

        $invoice = $this->getInvoiceDao()->update($id, $fields);

        return $invoice;
    }

    public function finishInvoice($id, $fields)
    {
        $finishFields = array(
            'status' => 'sent',
            'review_user_id' => $this->biz['user']['id'],
        );

        $fields = array_merge($fields, $finishFields);

        return $this->updateInvoice($id, $fields);
    }

    public function findInvoicesByUserId($userId)
    {
        return $this->getInvoiceDao()->findByUserId($userId);
    }

    public function countInvoice($conditions)
    {
        return $this->getInvoiceDao()->count($conditions);
    }

    public function searchInvoices($conditions, $orderBy, $start, $limit)
    {
        return $this->getInvoiceDao()->search($conditions, $orderBy, $start, $limit);
    }

    protected function getOrderService()
    {
        return $this->biz->service('Order:OrderService');
    }

    protected function getInvoiceDao()
    {
        return $this->biz->dao('Invoice:InvoiceDao');
    }

    protected function getInvoiceTemplateService()
    {
        return $this->biz->service('Invoice:InvoiceTemplateService');
    }

    protected function getLogService()
    {
        return $this->biz->service('System:LogService');
    }
}
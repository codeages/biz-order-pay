<?php

namespace Codeages\Biz\Invoice\Service\Impl;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;
use Codeages\Biz\Invoice\Service\InvoiceTemplateService;

class InvoiceTemplateServiceImpl extends BaseService implements InvoiceTemplateService
{
    public function createInvoiceTemplate($invoice)
    {
        $this->validateInvoiceTemplateFields($invoice);

        $user = $this->biz['user'];
        $template = $this->getDefaultTemplate($user['id']);
        if (empty($template)) {
            $invoice['isDefault'] = 1;
        }
        $invoice = $this->filterFields($invoice);

        return $this->getInvoiceTemplateDao()->create($invoice);
    }

    public function updateInvoiceTemplate($id, $invoice)
    {
        $this->validateInvoiceTemplateFields($invoice);
        $invoice = $this->filterFields($invoice);

        return $this->getInvoiceTemplateDao()->update($id, $invoice);
    }

    public function deleteInvoiceTemplate($invoiceId)
    {
        return $this->getInvoiceTemplateDao()->delete($invoiceId);
    }

    public function getInvoiceTemplate($id)
    {
        return $this->getInvoiceTemplateDao()->get($id);
    }

    public function searchInvoiceTemplates($conditions, $sort, $start, $limit)
    {
        return $this->getInvoiceTemplateDao()->search($conditions, $sort, $start, $limit);
    }

    public function searchInvoiceTemplateCount($conditions)
    {
        return $this->getInvoiceTemplateDao()->count($conditions);
    }

    public function setDefalutTemplate($id, $userId)
    {
        $template = $this->getDefaultTemplate($userId);
        if ($template) {
            $template['isDefault'] = '0';
            $this->updateInvoiceTemplate($template['id'], $template);
        }
        $template = $this->getInvoiceTemplate($id);
        $template['isDefault'] = '1';

        return $this->updateInvoiceTemplate($id, $template);
    }

    public function getDefaultTemplate($userId)
    {
        return $this->getInvoiceTemplateDao()->getByUserIdAndIsDefault($userId, 1);
    }

    protected function validateInvoiceTemplateFields($fields)
    {
        if (!ArrayToolkit::requireds($fields,
            array(
                'title',
                'type',
                'taxpayerIdentity',
                'mailAddress',
                'phone',
                'email',
                'receiver',
            ))
        ) {
            throw $this->createInvalidArgumentException('Lack of required fields');
        }
    }

    protected function filterFields($fields)
    {
        return ArrayToolkit::parts(
            $fields,
            array(
                'title',
                'type',
                'taxpayerIdentity',
                'content',
                'address',
                'bank',
                'mailAddress',
                'phone',
                'email',
                'receiver',
                'comment',
                'userId',
                'isDefault',
            )
        );
    }

    protected function getInvoiceTemplateDao()
    {
        return $this->biz->dao('Invoice:InvoiceTemplateDao');
    }
}
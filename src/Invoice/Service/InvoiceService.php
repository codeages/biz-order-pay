<?php
namespace Codeages\Biz\Invoice\Service;

interface InvoiceService
{
    public function getInvoice($id);

    public function tryApplyInvoice($orderIds);

    public function submitApply($apply);

    public function createInvoice($apply);

    public function findInvoicesByUserId($userId);

    public function countInvoice($conditions);

    public function searchInvoices($conditions, $orderBy, $start, $limit);

    public function updateInvoice($id, $fields);

    public function finishInvoice($id, $fields);
}
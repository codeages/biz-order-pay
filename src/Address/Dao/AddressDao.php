<?php

namespace Codeages\Biz\Address\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface AddressDao extends GeneralDaoInterface
{
    public function findByUserId($userId);

    public function removeDefaultAddress($userId, $addressId);
}

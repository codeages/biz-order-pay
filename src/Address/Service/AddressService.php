<?php

namespace Codeages\Biz\Address\Service;

interface AddressService
{
    public function getAddress($id);

    public function createAddress($address);

    public function updateAddress($id, $fields);

    public function deleteAddress($id);

    public function findAddressesByUserId($userId);

    public function searchAddresses($conditions, $orderBy, $start, $limit);

    public function countAddresses($conditions);

    public function changeDefaultAddress($userId, $addressId);
}

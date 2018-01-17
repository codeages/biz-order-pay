<?php

namespace Codeages\Biz\Address\Service\Impl;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Framework\Service\BaseService;
use Codeages\Biz\Address\Service\AddressService;

class AddressServiceImpl extends BaseService implements AddressService
{
    public function getAddress($id)
    {
        return $this->getAddressDao()->get($id);
    }

    public function createAddress($address)
    {
        $this->validateLogin();

        if (!ArrayToolkit::requireds($address, array(
            'user_id',
            'name',
            'mobile',
            'province',
            'detail',
            'is_default'
        ))) {
            throw $this->createInvalidArgumentException('args is invalid.');
        }

        $address = ArrayToolkit::parts($address, array(
            'user_id',
            'name',
            'mobile',
            'province',
            'city',
            'district',
            'town',
            'detail',
            'zip',
            'is_default'
        ));

        $address = $this->getAddressDao()->create($address);

        if ($address['is_default']) {
            $this->changeDefaultAddress($address['user_id'], $address['id']);
        }

        return $address;
    }

    public function updateAddress($id, $fields)
    {
        $this->validateLogin();
        $this->validateAddressExist($id);
        $this->validateAddressOwner($id);

        $fields = ArrayToolkit::filter($fields, array(
            'name' => '',
            'mobile' => '',
            'province' => '',
            'city' => '',
            'district' => '',
            'town' => '',
            'detail' => '',
            'zip' => '',
            'is_default' => 0
        ));

        $address = $this->getAddressDao()->update($id, $fields);

        if (!empty($fields['is_default'])) {
            $this->changeDefaultAddress($address['user_id'], $address['id']);
        }

        return $address;
    }

    public function deleteAddress($id)
    {
        $this->validateLogin();
        $this->validateAddressExist($id);
        $this->validateAddressOwner($id);

        return $this->getAddressDao()->delete($id);
    }

    public function findAddressesByUserId($userId)
    {
        return $this->getAddressDao()->findByUserId($userId);
    }

    public function searchAddresses($conditions, $orderBy, $start, $limit)
    {
        return $this->getAddressDao()->search($conditions, $orderBy, $start, $limit);
    }

    public function countAddresses($conditions)
    {
        return $this->getAddressDao()->count($conditions);
    }

    public function changeDefaultAddress($userId, $addressId)
    {
        $this->getAddressDao()->update($addressId, array('is_default' => 1));
        return $this->getAddressDao()->removeDefaultAddress($userId, $addressId);
    }

    protected function validateLogin()
    {
        if (empty($this->biz['user']['id'])) {
            throw $this->createAccessDeniedException('user is not login.');
        }
    }

    protected function validateAddressExist($id)
    {
        if (empty($this->getAddress($id))) {
            throw $this->createNotFoundException("address #{$id} is not found");
        }
    }

    protected function validateAddressOwner($id)
    {
        $address = $this->getAddress($id);

        if ($this->biz['user']['id'] != $address['user_id']) {
            throw $this->createServiceException('this address owner is not you');
        }
    }

    protected function getAddressDao()
    {
        return $this->biz->dao('Address:AddressDao');
    }
}

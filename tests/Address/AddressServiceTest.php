<?php

namespace Tests\Address;

use Tests\IntegrationTestCase;
use Codeages\Biz\Address\Service\AddressService;

class AddressServiceTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $currentUser = array(
            'id' => 1
        );
        $this->biz['user'] = $currentUser;
    }

    public function getAddress()
    {
        $address = $this->mockAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        $this->assertEquals($address['name'], $createdAddress['name']);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\AccessDeniedException
     */
    public function testCreateAddressWithoutLogin()
    {
        $address = $this->mockAddress();
        unset($this->biz['user']);
        $this->getAddressService()->createAddress($address);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\AccessDeniedException
     */
    public function testCreateAddressWithoutUserId()
    {
        $address = $this->mockAddress();
        unset($this->biz['user']);
        $this->getAddressService()->createAddress($address);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCreateAddressWithoutName()
    {
        $address = $this->mockAddress();
        unset($address['name']);
        $this->getAddressService()->createAddress($address);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCreateAddressWithoutPriceMobile()
    {
        $address = $this->mockAddress();
        unset($address['mobile']);
        $this->getAddressService()->createAddress($address);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCreateAddressWithoutProvince()
    {
        $address = $this->mockAddress();
        unset($address['province']);
        $this->getAddressService()->createAddress($address);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCreateAddressWithoutDetail()
    {
        $address = $this->mockAddress();
        unset($address['detail']);
        $this->getAddressService()->createAddress($address);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCreateAddressWithoutIsDefault()
    {
        $address = $this->mockAddress();
        unset($address['is_default']);
        $this->getAddressService()->createAddress($address);
    }

    public function testCreateAddress()
    {
        $address = $this->mockAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        $this->assertEquals($address['name'], $createdAddress['name']);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\AccessDeniedException
     */
    public function testUpdateAddressWithoutLogin()
    {
        $address = $this->mockAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        unset($this->biz['user']);
        $updatedAddress = $this->getAddressService()->updateAddress($createdAddress['id'], array('name' => '李四'));
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\NotFoundException
     */
    public function testUpdateAddressWithoutExist()
    {
        $updatedAddress = $this->getAddressService()->updateAddress(1, array('name' => '李四'));
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\ServiceException
     */
    public function testUpdateAddressWithoutIsNotOwner()
    {
        $address = $this->mockOtherAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        $updatedAddress = $this->getAddressService()->updateAddress($createdAddress['id'], array('name' => '李四'));
    }

    public function testUpdateAddress()
    {
        $address = $this->mockAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        $updatedAddress = $this->getAddressService()->updateAddress($createdAddress['id'], array('name' => '李四'));
        $this->assertEquals('李四', $updatedAddress['name']);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\AccessDeniedException
     */
    public function testDeleteAddressWithoutLogin()
    {
        $address = $this->mockAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        unset($this->biz['user']);
        $this->getAddressService()->deleteAddress($createdAddress['id']);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\NotFoundException
     */
    public function testDeleteAddressWithoutExist()
    {
        $this->getAddressService()->deleteAddress(1);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\ServiceException
     */
    public function testDeleteAddressIsNotOwner()
    {
        $address = $this->mockOtherAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        $this->getAddressService()->deleteAddress($createdAddress['id']);
    }

    public function testDeleteAddress()
    {
        $address = $this->mockAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        $this->getAddressService()->deleteAddress($createdAddress['id']);
        $this->assertEquals(null, $this->getAddressService()->getAddress($createdAddress['id']));
    }

    public function testFindAddressesByUserId()
    {
        $address = $this->mockAddress();
        $createdAddress = $this->getAddressService()->createAddress($address);
        $foundAddresses = $this->getAddressService()->findAddressesByUserId($this->biz['user']['id']);
        $this->assertEquals($foundAddresses[0]['name'], $createdAddress['name']);
    }

    protected function mockAddress()
    {
        return array(
            'user_id' => $this->biz['user']['id'],
            'name' => '张三',
            'mobile' => '13456520931',
            'province' => '浙江省',
            'city' => '杭州市',
            'district' => '滨江区',
            'town' => '浦沿街道',
            'detail' => '南环路3730号源越大厦301室',
            'zip' => '310051',
            'is_default' => 0
        );
    }

    protected function mockOtherAddress()
    {
        return array(
            'user_id' => 2,
            'name' => '李四',
            'mobile' => '13456520932',
            'province' => '浙江省',
            'city' => '杭州市',
            'district' => '滨江区',
            'town' => '浦沿街道',
            'detail' => '南环路3730号源越大厦301室',
            'zip' => '310051',
            'is_default' => 0
        );
    }

    /**
     * @return AddressService
     */
    protected function getAddressService()
    {
        return $this->biz->service('Address:AddressService');
    }
}

<?php

namespace Tests;

use Codeages\Biz\Pay\Payment\LianlianPayGetway;

class LianlianPayGetwayTest extends IntegrationTestCase
{
    /**
     * @expectedException PHPUnit\Framework\Error\Warning
     */
    public function testConverterNotify()
    {
        $gateway = new LianlianPayGetway($this->biz);

        $this->biz['payment.platforms'] = array(
            'lianlianpay' => array(
                'secret' => 'secret',
                'accessKey' => 'accessKey',
                'oid_partner' => 'oid_partner',
            )
        );

        $gateway->converterNotify(
            array(
                'oid_partner' => 'abc',
                'sign_type' => 'RSA',
                'sign' => '123',
                'dt_order' => '19701122092022',
                'no_order' => '123123123',
                'oid_paybill' => 'dsdfe',
                'money_order' => 'dsseww',
                'result_pay' => 'success',
                'settle_date' => '20171120',
                'info_order' => '221332',
                'pay_type' => 'money',
                'bank_code' => '2221332',
            )
        );
    }
}
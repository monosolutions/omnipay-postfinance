<?php

namespace Omnipay\Postfinance\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /** @var  PurchaseRequest */
    protected $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {

        $this->request->initialize(
            [
                'pspid' => 'MyPSPID',
                'shainsecret' => 'Mysecretsig1875!?',
                'transactionReference' => '1234',
                'amount' => 15.00,
                'currency' => 'EUR',
                'language' => 'en_US',
            ]
        );
        $data = $this->request->getData();
        $fields = [
            'pspid' => 'MyPSPID',
            'orderid' => '1234',
            'amount' => 1500,
            'currency' => 'EUR',
            'language' => 'en_US',
        ];

        foreach ($fields as $key => $value) {
            $key = strtoupper($key);
            $this->assertEquals($value, $data[$key], 'Key: ' . $key . ' not found in the data');
        }
        $hashes = ['sha1','sha256','sha512', 'test'];
        foreach($hashes as $hash) {
            $this->request->setHashFunction($hash);
            $data = $this->request->getData();
            if ($hash === 'test') {
                $hash = 'sha1';
            }
            $expectedHash = hash(
                $hash,
                'AMOUNT=1500Mysecretsig1875!?CURRENCY=EURMysecretsig1875!?LANGUAGE=en_USMysecretsig1875!?ORDERID=1234Mysecretsig1875!?PSPID=MyPSPIDMysecretsig1875!?'
            );
            $this->assertEquals($expectedHash, $data['SHASIGN'], 'Hash failed: ' . $hash);
        }
        
    }

}

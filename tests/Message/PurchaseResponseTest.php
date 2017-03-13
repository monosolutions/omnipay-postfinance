<?php

namespace Omnipay\Postfinance\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    /** @var  PurchaseRequest */
    protected $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
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
    }

    public function testLiveEndpoint()
    {
        $response = $this->request->send();
        $this->assertTrue($response->isRedirect());
        $this->assertInstanceOf(RedirectResponseInterface::class, $response);
        $this->assertContains('prod', $response->getRedirectUrl());
    }

    public function testTestEndpoint()
    {
        $this->request->setTestMode(true);
        $response = $this->request->send();
        $this->assertTrue($response->isRedirect());
        $this->assertInstanceOf(RedirectResponseInterface::class, $response);
        $this->assertContains('test', $response->getRedirectUrl());


    }
}

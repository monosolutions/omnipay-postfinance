<?php
namespace Omnipay\Postfinance;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var  Gateway */
    protected $gateway;

    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00', 'transactionReference' => 1,'currency' => 'EUR'));
        $this->assertInstanceOf('Omnipay\Postfinance\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertTrue(count($request->getData()) > 0);
    }
}

<?php
namespace Omnipay\Postfinance\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Epay Purchase Response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    const LIVE_ENDPOINT = 'https://e-payment.postfinance.ch/ncol/prod/orderstandard.asp';
    const TEST_ENDPOINT = 'https://e-payment.postfinance.ch/ncol/test/orderstandard.asp';
    protected $redirectMethod = 'POST';

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getEndpoint()
    {
        if ($this->getRequest()->getTestMode()) {
            return self::TEST_ENDPOINT;
        }
        return self::LIVE_ENDPOINT;
    }

    public function getRedirectUrl()
    {
        return $this->getEndpoint();
    }

    public function getRedirectMethod()
    {
        return $this->redirectMethod;
    }

    public function getRedirectData()
    {
        return $this->data;
    }
}

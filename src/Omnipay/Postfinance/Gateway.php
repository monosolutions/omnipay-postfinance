<?php

namespace Omnipay\Postfinance;

use Omnipay\Common\AbstractGateway;
use Omnipay\Postfinance\Message\CaptureRequest;
use Omnipay\Postfinance\Message\CompletePurchaseRequest;
use Omnipay\Postfinance\Message\DeleteRequest;
use Omnipay\Postfinance\Message\PurchaseRequest;
use Omnipay\Postfinance\Message\RefundRequest;

/**
 * Postfinance Gateway
 */
class Gateway extends AbstractGateway
{

    public function getName()
    {
        return 'Postfinance';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'pspid' => '',
            'sha_in_secret' => '',
            'sha_out_secret' => '',
            'testMode' => true,
        );
    }

    public function getPSPId()
    {
        return $this->getParameter('pspid');
    }

    public function setPSPId($pspId)
    {
        return $this->setParameter('pspid', $pspId);
    }

    public function getShaInSecret()
    {
        return $this->getParameter('sha_in_secret');
    }

    public function setShaInSecret($shaInSecret)
    {
        return $this->setParameter('sha_in_secret', $shaInSecret);
    }

    public function getShaOutSecret()
    {
        return $this->getParameter('sha_out_secret');
    }

    public function setShaOutSecret($shaOutSecret)
    {
        return $this->setParameter('sha_out_secret', $shaOutSecret);
    }



    // This is the same in both instances since epay recommendes using the payment window.
    public function authorize(array $parameters = array())
    {
        return $this->purchase($parameters);
    }

    // This is the same in both instances since epay recommendes using the payment window.
    public function completeAuthorize(array $parameters = array())
    {
        return $this->purchase($parameters);
    }

    /**
     * @param array $parameters
     * @return PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Postfinance\Message\PurchaseRequest', $parameters);
    }

}

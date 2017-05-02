<?php

namespace Omnipay\Postfinance\Message;

use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\ParameterBag;


/**
 * Epay Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getPSPId()
    {
        return $this->parameters->get('pspid');
    }

    public function setPSPId($pspId)
    {
        return $this->setParameter('pspid', $pspId);
    }

    public function getHashFunction()
    {
        return $this->parameters->get('hash_function');
    }

    public function setHashFunction($hashFunction)
    {
        return $this->setParameter('hash_function', $hashFunction);
    }

    public function getShaInSecret()
    {
        return $this->getParameter('sha_in_secret');
    }

    public function setShaInSecret($secret)
    {
        return $this->setParameter('sha_in_secret', $secret);
    }

    public function getShaOutSecret()
    {
        return $this->getParameter('sha_out_secret');
    }

    public function setShaOutSecret($secret)
    {
        return $this->setParameter('sha_out_secret', $secret);
    }

    public function setLanguage($language)
    {
        return $this->setParameter('language', $language);
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setAcceptUrl($acceptUrl)
    {
        return $this->setParameter('acceptUrl', $acceptUrl);
    }

    public function setExceptionUrl($acceptUrl)
    {
        return $this->setParameter('exceptionUrl', $acceptUrl);
    }

    public function setCancelUrl($acceptUrl)
    {
        return $this->setParameter('cancelUrl', $acceptUrl);
    }

    public function setDeclineUrl($acceptUrl)
    {
        return $this->setParameter('declineUrl', $acceptUrl);
    }

    public function getData()
    {
        $this->validate('pspid', 'amount', 'transactionReference', 'currency');

        $data = $this->parameters->all();
        $data['amount'] = $this->getAmountInteger();
        $data['currency'] = $this->getCurrency();
        $data['orderid'] = $this->getTransactionReference();
        $nextData = [];
        // It would have made sense to user array_change_key_case, but since we also filter some fields, this makes more sense.
        foreach ($data as $k => $v) {
            switch ($k) {
                case 'transactionReference':
                    $k = 'orderid';
                    break;
                case 'sha_out_secret':
                case 'sha_in_secret':
                case 'hash_function':
                case 'testMode':
                    continue 2;
            }
            $nextData[strtoupper($k)] = $v;
        }
        if ($this->getShaInSecret()) {
            $nextData['SHASIGN'] = $this->generateHash($nextData);
        }


        return $nextData;
    }

    protected function get($key)
    {
        $getName = 'get' . ucfirst($key);
        if (!method_exists($this, $getName)) {
            return $this->getParameter($key);
        }
        return $this->{$getName}();
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    public function send()
    {
        return $this->sendData($this->getData());
    }

    protected function generateHash($data)
    {
        ksort($data, SORT_STRING);
        $hashData = [];
        $secret = $this->getShaInSecret();
        foreach ($data as $k => $v) {
            $hashData[] = "{$k}={$v}{$secret}";
        }
        $hashFunction = 'sha1';
        if (in_array(strtolower($this->getHashFunction()), ['sha1','sha256','sha512'])) {
            $hashFunction = $this->getHashFunction();
        }
        return hash($hashFunction, implode($hashData));
    }
}

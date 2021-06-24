<?php

namespace Omnipay\IPay88\Message;


use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    public function getTransactionId()
    {
        return $this->data['RefNo'];
    }

    public function isTransparentRedirect()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function getRedirectUrl()
    {
        if ($this->data['testMode']) {
            $endpoint = 'https://sandbox.ipay88.com.kh:8468/epayment/entry.asp';
        } else {
            $endpoint = 'https://payment.ipay88.com.kh/epayment/entry.asp';
        }
        return $endpoint;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->data;
    }

}
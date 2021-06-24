<?php

namespace Omnipay\IPay88\Message;

use Omnipay\Common\Currency;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->guardParameters();

        $data = $this->httpRequest->request->all();

        $data['ComputedSignature'] = $this->signature(
            $this->getMerchantKey(),
            $this->getMerchantCode(),
            $data['PaymentId'],
            $data['RefNo'],
            $data['Amount'],
            $data['Currency'],
            $data['Status']
        );

        return $data;
    }

    public function sendData($data)
    {
        if ($this->getTestMode()) {
            $endpoint = 'https://sandbox.ipay88.com.kh:8468/epayment/enquiry.asp';
        } else {
            $endpoint = 'https://payment.ipay88.com.kh/epayment/enquiry.asp';
        }

        $data['ReQueryStatus'] = $this->httpClient
            ->request('post', $endpoint.'?'.http_build_query([
                'MerchantCode' => $this->getMerchantCode(),
                'RefNo' => $data['RefNo'],
                'Amount' => $data['Amount']
                ]), [], json_encode([]))
            ->getBody()
            ->getContents();

        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    protected function signature($merchantKey, $merchantCode, $paymentId, $refNo, $amount, $currency, $status)
    {
        $amount = str_replace([',', '.'], '', $amount);

        $paramsInArray = [$merchantKey, $merchantCode, $paymentId, $refNo, $amount, $currency, $status];

        return $this->createSignatureFromString(implode('', $paramsInArray));
    }
}

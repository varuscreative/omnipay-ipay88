<?php

namespace Omnipay\IPay88\Message;


class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $this->guardParameters();

        return [
            'MerchantCode' => $this->getMerchantCode(),
            'PaymentId' => '',
            'RefNo' => $this->getTransactionId(),
            'Amount' => $this->getAmount(),
            'Currency' => $this->getCurrency(),
            'ProdDesc' => $this->getDescription(),
            'UserName' => $this->getCard()->getBillingName(),
            'UserEmail' => $this->getCard()->getEmail(),
            'UserContact' => $this->getCard()->getBillingPhone(),
            'Remark' => '',
            'Lang' => '',
            'Signature' => $this->signature(
                $this->getMerchantKey(),
                $this->getMerchantCode(),
                $this->getTransactionId(),
                $this->getAmount(),
                $this->getCurrency()
            ),
            'ResponseURL' => $this->getReturnUrl(),
            'BackendURL' => $this->getBackendUrl(),
            'testMode' => $this->getTestMode(),
        ];
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    private function signature($merchantKey, $merchantCode, $refNo, $amount, $currency)
    {
        $amount = str_replace([',', '.'], '', $amount);

        $paramsInArray = [$merchantKey, $merchantCode, $refNo, $amount, $currency];

        return $this->createSignatureFromString(implode('', $paramsInArray));
    }

}
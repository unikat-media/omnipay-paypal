<?php

namespace Omnipay\PayPal\Message;

use \Omnipay\Common\Exception\InvalidRequestException;

class IPNRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://www.paypal.com/cgi-bin/webscr';
    protected $testEndpoint = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    public function httpRequestValidate()
    {
        foreach (func_get_args() as $key) {
            if (!isset($_REQUEST[$key])) {
                throw new InvalidRequestException("The $key request parameter is required");
            }
        }
    }

    public function getData()
    {
        $this->httpRequestValidate(
            'mc_gross',
            'payer_id',
            'payment_status',
            'notify_version',
            'txn_id',
            'payment_type',
            'receiver_email',
            'payment_fee',
            'receiver_id',
            'item_name',
            'mc_currency',
            'item_number',
            'payment_gross',
            'shipping');

        $data = $_REQUEST;
        $data['cmd'] = '_notify-validate';

        return $data;
    }

    public function sendData($data)
    {
        $url = $this->getEndpoint().'?'.http_build_query($data, '', '&');
        $httpResponse = $this->httpClient->post($url)->send();

        return $this->createResponse($httpResponse->getBody());
    }

    protected function createResponse($data)
    {
        return $this->response = new IPNResponse($this, $data);
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}

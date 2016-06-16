<?php

namespace Omnipay\PayPal;

abstract class AbstractGateway extends \Omnipay\Common\AbstractGateway
{
    public function notification(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\IPNRequest', $parameters);
    }
}

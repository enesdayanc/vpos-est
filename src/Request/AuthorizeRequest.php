<?php

namespace PaymentGateway\VPosEst\Request;

use PaymentGateway\VPosEst\Constant;
use PaymentGateway\VPosEst\Constant\RequestType;
use PaymentGateway\VPosEst\Setting\Credential;

class AuthorizeRequest extends PurchaseRequest implements RequestInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->type = RequestType::PRE_AUTH;
    }
}
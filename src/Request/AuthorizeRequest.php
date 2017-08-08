<?php

namespace VPosEst\Request;

use VPosEst\Constant;
use VPosEst\Constant\RequestType;
use VPosEst\Setting\Credential;

class AuthorizeRequest extends PurchaseRequest implements RequestInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->type = RequestType::PRE_AUTH;
    }
}
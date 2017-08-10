<?php

namespace Enesdayanc\VPosEst\Request;

use Enesdayanc\VPosEst\Constant;
use Enesdayanc\VPosEst\Constant\RequestType;
use Enesdayanc\VPosEst\Setting\Credential;

class AuthorizeRequest extends PurchaseRequest implements RequestInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->type = RequestType::PRE_AUTH;
    }
}
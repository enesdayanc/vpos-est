<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 16:34
 */

namespace PaymentGateway\VPosEst\Constant;

class RequestType
{
    const PRE_AUTH = 'PreAuth';
    const POST_AUTH = 'PostAuth';
    const AUTH = 'Auth';
    const CREDIT = 'Credit';
    const VOID = 'Void';
}
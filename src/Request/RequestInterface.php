<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 13:56
 */

namespace PaymentGateway\VPosEst\Request;


use PaymentGateway\VPosEst\Setting\Credential;

interface RequestInterface
{
    public function getType();
    public function validate();
    public function toXmlString(Credential $credential);
}
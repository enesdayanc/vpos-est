<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 13:56
 */

namespace VPosEst\Request;


use VPosEst\Setting\Credential;

interface RequestInterface
{
    public function toXmlString(Credential $credential);
}
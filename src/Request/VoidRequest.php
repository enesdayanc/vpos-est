<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 03/08/2017
 * Time: 10:20
 */

namespace PaymentGateway\VPosEst\Request;


use PaymentGateway\VPosEst\Constant\RequestMode;
use PaymentGateway\VPosEst\Constant\RequestType;
use PaymentGateway\VPosEst\Helper\Helper;
use PaymentGateway\VPosEst\Helper\Validator;
use PaymentGateway\VPosEst\Setting\Credential;

class VoidRequest implements RequestInterface
{
    private $type;
    private $orderId;


    public function __construct()
    {
        $this->type = RequestType::VOID;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    public function toXmlString(Credential $credential)
    {
        $this->validate();

        $elements = array(
            "Name" => $credential->getUsername(),
            "Password" => $credential->getPassword(),
            "ClientId" => $credential->getClientId(),
            "Mode" => RequestMode::P,
            "OrderId" => $this->getOrderId(),
            "Type" => $this->getType(),
        );

        return Helper::arrayToXmlString($elements);
    }

    public function validate()
    {
        Validator::validateOrderId($this->getOrderId());
    }
}
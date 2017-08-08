<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 03/08/2017
 * Time: 10:03
 */

namespace VPosEst\Request;


use VPosEst\Constant\RequestType;
use VPosEst\Helper\Helper;
use VPosEst\Helper\Validator;
use VPosEst\Setting\Credential;

class CaptureRequest implements RequestInterface
{
    private $type;
    private $mode;
    private $orderId;
    private $amount;
    private $currency;


    /**
     * CaptureRequest constructor.
     */
    public function __construct()
    {
        $this->type = RequestType::POST_AUTH;
    }


    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        Validator::validateRequestMode($mode);
        $this->mode = $mode;
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
        Validator::validateOrderId($orderId);
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        Validator::validateAmount($amount);
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        Validator::validateCurrency($currency);
        $this->currency = $currency;
    }


    public function toXmlString(Credential $credential)
    {
        $elements = array(
            "Name" => $credential->getUsername(),
            "Password" => $credential->getPassword(),
            "ClientId" => $credential->getClientId(),
            "Mode" => $this->getMode(),
            "OrderId" => $this->getOrderId(),
            "Type" => $this->getType(),
            "Currency" => $this->getCurrency(),
            "Total" => $this->getAmount(),
        );

        return Helper::arrayToXmlString($elements);
    }
}
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
use VPosEst\Model\ISO4217Currency;
use VPosEst\Setting\Credential;

class CaptureRequest implements RequestInterface
{
    private $type;
    private $mode;
    private $orderId;
    private $amount;
    /** @var  ISO4217Currency $currency */
    private $currency;


    /**
     * CaptureRequest constructor.
     */
    public function __construct()
    {
        $this->type = RequestType::POST_AUTH;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
        $this->amount = $amount;
    }

    /**
     * @return ISO4217Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param ISO4217Currency $currency
     */
    public function setCurrency(ISO4217Currency $currency)
    {
        $this->currency = $currency;
    }


    public function toXmlString(Credential $credential)
    {
        $this->validate();

        $elements = array(
            "Name" => $credential->getUsername(),
            "Password" => $credential->getPassword(),
            "ClientId" => $credential->getClientId(),
            "Mode" => $this->getMode(),
            "OrderId" => $this->getOrderId(),
            "Type" => $this->getType(),
            "Currency" => $this->getCurrency()->getNumeric(),
            "Total" => $this->getAmount(),
        );

        return Helper::arrayToXmlString($elements);
    }

    public function validate()
    {
        Validator::validateCurrency($this->getCurrency());
        Validator::validateAmount($this->getAmount());
        Validator::validateOrderId($this->getOrderId());
        Validator::validateRequestMode($this->getMode());
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 03/08/2017
 * Time: 10:03
 */

namespace PaymentGateway\VPosEst\Request;


use PaymentGateway\ISO4217\Model\Currency;
use PaymentGateway\VPosEst\Constant\RequestMode;
use PaymentGateway\VPosEst\Constant\RequestType;
use PaymentGateway\VPosEst\Helper\Helper;
use PaymentGateway\VPosEst\Helper\Validator;
use PaymentGateway\VPosEst\Setting\Credential;

class CaptureRequest implements RequestInterface
{
    private $type;
    private $orderId;
    private $amount;
    /** @var  Currency $currency */
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
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency)
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
            "Mode" => RequestMode::P,
            "OrderId" => $this->getOrderId(),
            "Type" => $this->getType(),
            "Currency" => $this->getCurrency()->getNumeric(),
            "Total" => Helper::amountParser($this->getAmount()),
        );

        return Helper::arrayToXmlString($elements);
    }

    public function validate()
    {
        Validator::validateCurrency($this->getCurrency());
        Validator::validateAmount($this->getAmount());
        Validator::validateOrderId($this->getOrderId());
    }
}
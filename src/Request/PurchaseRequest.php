<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 02/08/2017
 * Time: 14:36
 */

namespace VPosEst\Request;

use VPosEst\Constant\RequestType;
use VPosEst\Helper\Helper;
use VPosEst\Helper\Validator;
use VPosEst\Model\Card;
use VPosEst\Setting\Credential;

class PurchaseRequest implements RequestInterface
{
    protected $type;
    private $mode;
    private $orderId;
    private $currency;
    private $groupId;
    private $transId;
    private $userId;
    private $extra;
    private $installment;
    private $amount;
    /** @var  Card $card */
    private $card;
    private $email;
    private $ip;
    private $language;


    /**
     * PurchaseRequest constructor.
     */
    public function __construct()
    {
        $this->type = RequestType::AUTH;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param mixed $groupId
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * @return mixed
     */
    public function getTransId()
    {
        return $this->transId;
    }

    /**
     * @param mixed $transId
     */
    public function setTransId($transId)
    {
        $this->transId = $transId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param mixed $extra
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    /**
     * @return mixed
     */
    public function getInstallment()
    {
        return $this->installment;
    }

    /**
     * @param mixed $installment
     */
    public function setInstallment($installment)
    {
        $this->installment = $installment;
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
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }


    /**
     * @param Card $card
     */
    public function setCard(Card $card)
    {
        $this->card = $card;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function toXmlString(Credential $credential)
    {
        $card = $this->getCard();

        $expires = $card->getExpiryMonth() . $card->getExpiryYear();

        $elements = array(
            "Name" => $credential->getUsername(),
            "Password" => $credential->getPassword(),
            "ClientId" => $credential->getClientId(),
            "Mode" => $this->getMode(),
            "OrderId" => $this->getOrderId(),
            "Type" => $this->getType(),
            "Currency" => $this->getCurrency(),
            "GroupId" => $this->getGroupId(),
            "TransId" => $this->getTransId(),
            "UserId" => $this->getUserId(),
            "Extra" => $this->getExtra(),
            "Taksit" => $this->getInstallment(),
            "Number" => $card->getCreditCardNumber(),
            "Expires" => $expires,
            "Cvv2Val" => $card->getCvv(),
            "Total" => $this->getAmount(),
            "Email" => $this->getEmail(),
            "IPAddress" => $this->getIp(),
        );

        return Helper::arrayToXmlString($elements);
    }

    public function validate()
    {
        Validator::validateIp($this->getIp());
        Validator::validateLanguage($this->getLanguage());
        Validator::validateEmail($this->getEmail());
        Validator::validateAmount($this->getAmount());
        Validator::validateInstallment($this->getInstallment());
        Validator::validateUserId($this->getUserId());
        Validator::validateCurrency($this->getCurrency());
        Validator::validateOrderId($this->getOrderId());
        Validator::validateRequestMode($this->getMode());
    }
}
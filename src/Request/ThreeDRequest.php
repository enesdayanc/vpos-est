<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 16/08/2017
 * Time: 14:54
 */

namespace PaymentGateway\VPosEst\Request;

use PaymentGateway\ISO4217\Model\Currency;
use PaymentGateway\VPosEst\Constant\CardholderPresentCode;
use PaymentGateway\VPosEst\Constant\RequestMode;
use PaymentGateway\VPosEst\Helper\Helper;
use PaymentGateway\VPosEst\Helper\Validator;
use PaymentGateway\VPosEst\Setting\Credential;

class ThreeDRequest implements RequestInterface
{

    private $ip;
    private $email;
    private $orderId;
    private $type;
    private $md;
    private $amount;
    /** @var  Currency $currency */
    private $currency;
    private $installment;
    private $xid;
    private $eci;
    private $cavv;
    private $language;

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getMd()
    {
        return $this->md;
    }

    /**
     * @param mixed $md
     */
    public function setMd($md)
    {
        $this->md = $md;
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
    public function getXid()
    {
        return $this->xid;
    }

    /**
     * @param mixed $xid
     */
    public function setXid($xid)
    {
        $this->xid = $xid;
    }

    /**
     * @return mixed
     */
    public function getEci()
    {
        return $this->eci;
    }

    /**
     * @param mixed $eci
     */
    public function setEci($eci)
    {
        $this->eci = $eci;
    }

    /**
     * @return mixed
     */
    public function getCavv()
    {
        return $this->cavv;
    }

    /**
     * @param mixed $cavv
     */
    public function setCavv($cavv)
    {
        $this->cavv = $cavv;
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

    public function validate()
    {
        Validator::validateIp($this->getIp());
        Validator::validateEmail($this->getEmail());
        Validator::validateOrderId($this->getOrderId());
        Validator::validateRequestType($this->getType());
        Validator::validateNotEmpty('MD', $this->getMd());
        Validator::validateAmount($this->getAmount());
        Validator::validateCurrency($this->getCurrency());
        Validator::validateInstallment($this->getInstallment());
        Validator::validateNotEmpty('XID', $this->getXid());
        Validator::validateNotEmpty('ECI', $this->getEci());
        Validator::validateNotEmpty('CAVV', $this->getCavv());
    }

    public function toXmlString(Credential $credential)
    {
        $this->validate();

        $elements = array(
            "Name" => $credential->getUsername(),
            "Password" => $credential->getPassword(),
            "ClientId" => $credential->getClientId(),
            "IPAddress" => $this->getIp(),
            "Email" => $this->getEmail(),
            "Mode" => RequestMode::P,
            "OrderId" => $this->getOrderId(),
            "GroupId" => '',
            "TransId" => '',
            "UserId" => '',
            "Type" => $this->getType(),
            "Number" => $this->getMd(),
            "Expires" => '',
            "Cvv2Val" => '',
            "Total" => Helper::amountParser($this->getAmount()),
            "Currency" => $this->getCurrency()->getNumeric(),
            "Taksit" => $this->getInstallment(),
            "PayerTxnId" => $this->getXid(),
            "PayerSecurityLevel" => $this->getEci(),
            "PayerAuthenticationCode" => $this->getCavv(),
            "CardholderPresentCode" => CardholderPresentCode::THREE_D,
            "BillTo" => array(
                "Name" => '',
                "Street1" => '',
                "Street2" => '',
                "Street3" => '',
                "City" => '',
                "StateProv" => '',
                "PostalCode" => '',
                "Country" => '',
                "Company" => '',
                "TelVoice" => '',
            ),
            "ShipTo" => array(
                "Name" => '',
                "Street1" => '',
                "Street2" => '',
                "Street3" => '',
                "City" => '',
                "StateProv" => '',
                "PostalCode" => '',
                "Country" => '',
            ),
            "Extra" => '',
        );

        return Helper::arrayToXmlString($elements);
    }
}
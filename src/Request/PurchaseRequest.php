<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 02/08/2017
 * Time: 14:36
 */

namespace PaymentGateway\VPosEst\Request;

use PaymentGateway\ISO4217\Model\Currency;
use PaymentGateway\VPosEst\Constant\RedirectFormMethod;
use PaymentGateway\VPosEst\Constant\RequestMode;
use PaymentGateway\VPosEst\Constant\RequestType;
use PaymentGateway\VPosEst\Constant\StoreType;
use PaymentGateway\VPosEst\Helper\Helper;
use PaymentGateway\VPosEst\Helper\Validator;
use PaymentGateway\VPosEst\Model\Card;
use PaymentGateway\VPosEst\Model\RedirectForm;
use PaymentGateway\VPosEst\Setting\Credential;
use PaymentGateway\VPosEst\Setting\Setting;

class PurchaseRequest implements RequestInterface
{
    protected $type;
    private $orderId;
    /** @var  Currency $currency */
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

    public function toXmlString(Credential $credential, bool $maskCardData = false)
    {
        $this->validate();

        $card = $this->getCard();

        $elements = array(
            "Name" => $credential->getUsername(),
            "Password" => $credential->getPassword(),
            "ClientId" => $credential->getClientId(),
            "Mode" => RequestMode::P,
            "OrderId" => $this->getOrderId(),
            "Type" => $this->getType(),
            "Currency" => $this->getCurrency()->getNumeric(),
            "GroupId" => $this->getGroupId(),
            "TransId" => $this->getTransId(),
            "UserId" => $this->getUserId(),
            "Extra" => $this->getExtra(),
            "Taksit" => $this->getInstallment(),
            "Number" => $card->getCreditCardNumber($maskCardData),
            "Expires" => $card->getExpires($maskCardData),
            "Cvv2Val" => $card->getCvv($maskCardData),
            "Total" => Helper::amountParser($this->getAmount()),
            "Email" => $this->getEmail(),
            "IPAddress" => $this->getIp(),
        );

        return Helper::arrayToXmlString($elements);
    }

    public function validate()
    {
        Validator::validateNotEmpty('card', $this->getCard());
        $this->getCard()->validate();
        Validator::validateIp($this->getIp());
        Validator::validateLanguage($this->getLanguage());
        Validator::validateEmail($this->getEmail());
        Validator::validateAmount($this->getAmount());
        Validator::validateInstallment($this->getInstallment());
        Validator::validateUserId($this->getUserId());
        Validator::validateCurrency($this->getCurrency());
        Validator::validateOrderId($this->getOrderId());
    }

    public function get3DRedirectForm(Setting $setting)
    {
        $this->validate();

        $credential = $setting->getCredential();

        Validator::validateNotEmpty('storeKey', $credential->getStoreKey());

        $rnd = md5(microtime());
        $card = $this->getCard();

        $params = array(
            'pan' => $card->getCreditCardNumber(),
            'cv2' => $card->getCvv(),
            'Ecom_Payment_Card_ExpDate_Year' => $card->getExpiryYear(),
            'Ecom_Payment_Card_ExpDate_Month' => $card->getExpiryMonth(),
            'clientid' => $credential->getClientId(),
            'oid' => $this->getOrderId(),
            'okUrl' => $setting->getThreeDSuccessUrl(),
            'failUrl' => $setting->getThreeDFailUrl(),
            'rnd' => $rnd,
            'islemtipi' => $this->getType(),
            'taksit' => $this->getInstallment(),
            'storetype' => $setting->getStoreType(),
            'lang' => $this->getLanguage(),
            'hash' => $this->get3DHash($rnd, $setting),
            'amount' => Helper::amountParser($this->getAmount()),
            'currency' => $this->getCurrency()->getNumeric(),
            'email' => $this->getEmail(),
        );

        $redirectForm = new RedirectForm();
        $redirectForm->setAction($setting->getThreeDPostUrl());
        $redirectForm->setMethod(RedirectFormMethod::POST);
        $redirectForm->setParameters($params);

        return $redirectForm;
    }

    private function get3DHash($rnd, Setting $setting)
    {
        $credential = $setting->getCredential();

        $hashString = Helper::get3DHashString(
            $credential->getClientId(),
            $this->getOrderId(),
            Helper::amountParser($this->getAmount()),
            $setting->getThreeDSuccessUrl(),
            $setting->getThreeDFailUrl(),
            $this->getType(),
            $this->getInstallment(),
            $rnd,
            $credential->getStoreKey()
        );

        return Helper::get3DCryptedHash($hashString);
    }
}
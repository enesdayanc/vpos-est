<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 02/08/2017
 * Time: 14:36
 */

namespace VPosEst\Request;

use VPosEst\Constant\RedirectFormMethod;
use VPosEst\Constant\RequestType;
use VPosEst\Constant\StoreType;
use VPosEst\Helper\Helper;
use VPosEst\Helper\Validator;
use VPosEst\Model\Card;
use VPosEst\Model\ISO4217Currency;
use VPosEst\Model\RedirectForm;
use VPosEst\Setting\Credential;
use VPosEst\Setting\Setting;

class PurchaseRequest implements RequestInterface
{
    protected $type;
    private $mode;
    private $orderId;
    /** @var  ISO4217Currency $currency */
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
        $this->validate();

        $card = $this->getCard();

        $elements = array(
            "Name" => $credential->getUsername(),
            "Password" => $credential->getPassword(),
            "ClientId" => $credential->getClientId(),
            "Mode" => $this->getMode(),
            "OrderId" => $this->getOrderId(),
            "Type" => $this->getType(),
            "Currency" => $this->getCurrency()->getNumeric(),
            "GroupId" => $this->getGroupId(),
            "TransId" => $this->getTransId(),
            "UserId" => $this->getUserId(),
            "Extra" => $this->getExtra(),
            "Taksit" => $this->getInstallment(),
            "Number" => $card->getCreditCardNumber(),
            "Expires" => $card->getExpires(),
            "Cvv2Val" => $card->getCvv(),
            "Total" => $this->getAmount(),
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
        Validator::validateRequestMode($this->getMode());
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
            'storetype' => StoreType::THREE_D_PAY,
            'lang' => $this->getLanguage(),
            'hash' => $this->get3DHash($rnd, $setting),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency()->getNumeric()
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
            $this->getAmount(),
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
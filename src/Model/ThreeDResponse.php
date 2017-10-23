<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 09/08/2017
 * Time: 11:53
 */

namespace PaymentGateway\VPosEst\Model;


use PaymentGateway\ISO4217\Model\Currency;
use PaymentGateway\VPosEst\Constant\MdStatus;
use PaymentGateway\VPosEst\Constant\StoreType;
use PaymentGateway\VPosEst\Constant\Success;
use PaymentGateway\VPosEst\Exception\ValidationException;
use PaymentGateway\VPosEst\Helper\Helper;
use PaymentGateway\VPosEst\HttpClient;
use PaymentGateway\VPosEst\Request\ThreeDRequest;
use PaymentGateway\VPosEst\Response\Response;
use PaymentGateway\VPosEst\Setting\Setting;

class ThreeDResponse
{
    private $allowedMdStatus = array(
        MdStatus::ONE,
        MdStatus::TWO,
        MdStatus::THREE,
        MdStatus::FOUR,
    );

    private $clientId;
    private $orderId;
    private $authCode;
    private $procReturnCode;
    private $response;
    private $mdStatus;
    private $cavv;
    private $eci;
    private $md;
    private $rnd;
    private $hash;
    private $hashParams;
    private $hashParamsVal;
    private $transId;
    private $type;
    private $userIp;
    private $userEmail;
    private $amount;
    private $installment;
    private $xid;
    /** @var  Currency */
    private $currency;

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
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * @param mixed $userIp
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param mixed $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
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
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
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
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * @param mixed $authCode
     */
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;
    }

    /**
     * @return mixed
     */
    public function getProcReturnCode()
    {
        return $this->procReturnCode;
    }

    /**
     * @param mixed $procReturnCode
     */
    public function setProcReturnCode($procReturnCode)
    {
        $this->procReturnCode = $procReturnCode;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getMdStatus()
    {
        return $this->mdStatus;
    }

    /**
     * @param mixed $mdStatus
     */
    public function setMdStatus($mdStatus)
    {
        $this->mdStatus = $mdStatus;
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
    public function getRnd()
    {
        return $this->rnd;
    }

    /**
     * @param mixed $rnd
     */
    public function setRnd($rnd)
    {
        $this->rnd = $rnd;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getHashParams()
    {
        return $this->hashParams;
    }

    /**
     * @param mixed $hashParams
     */
    public function setHashParams($hashParams)
    {
        $this->hashParams = $hashParams;
    }

    /**
     * @return mixed
     */
    public function getHashParamsVal()
    {
        return $this->hashParamsVal;
    }

    /**
     * @param mixed $hashParamsVal
     */
    public function setHashParamsVal($hashParamsVal)
    {
        $this->hashParamsVal = $hashParamsVal;
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
     * @param Setting $setting
     * @param $orderId
     * @return Response
     * @throws ValidationException
     */
    public function getResponseClass(Setting $setting, $orderId)
    {
        $validSignature = $this->isValidSignature($setting);

        $responseClass = new Response();

        $responseClass->setCode($this->getAuthCode());
        $responseClass->setTransactionReference($this->getTransId());

        if ($this->getOrderId() != $orderId) {
            $responseClass->setErrorMessage('Order id not match');
        } elseif ($validSignature) {

            if (in_array($this->getMdStatus(), $this->allowedMdStatus)) {
                if ($setting->getStoreType() == StoreType::THREE_D_PAY) {
                    $responseClass = $this->getResponseClass3DPayModel($setting);
                } elseif ($setting->getStoreType() == StoreType::THREE_D) {
                    $responseClass = $this->getResponseClassFor3DModel($setting);
                } else {
                    throw new ValidationException('Invalid store type' . $setting->getStoreType(), 'INVALID_STORE_TYPE');
                }
            }
        } else {
            $responseClass->setErrorMessage('Invalid Signature');
        }

        return $responseClass;
    }


    /**
     * @param Setting $setting
     * @return Response
     */
    private function getResponseClass3DPayModel(Setting $setting)
    {
        $responseClass = new Response();

        $responseClass->setCode($this->getAuthCode());
        $responseClass->setTransactionReference($this->getTransId());

        if ($this->getProcReturnCode() === Success::PROC_RETURN_CODE || $this->getResponse() === Success::RESPONSE) {
            $responseClass->setSuccessful(true);
        } else {
            $responseClass->setSuccessful(false);
        }

        return $responseClass;
    }

    /**
     * @param Setting $setting
     * @return Response
     */
    private function getResponseClassFor3DModel(Setting $setting)
    {
        $threeDRequest = new ThreeDRequest();
        $threeDRequest->setIp($this->getUserIp());
        $threeDRequest->setEmail($this->getUserEmail());
        $threeDRequest->setOrderId($this->getOrderId());
        $threeDRequest->setType($this->getType());
        $threeDRequest->setMd($this->getMd());
        $threeDRequest->setAmount($this->getAmount());
        $threeDRequest->setCurrency($this->getCurrency());
        $threeDRequest->setInstallment($this->getInstallment());
        $threeDRequest->setXid($this->getXid());
        $threeDRequest->setEci($this->getEci());
        $threeDRequest->setCavv($this->getCavv());

        $httpClient = new HttpClient($setting);

        return $httpClient->send($threeDRequest, $setting->getPurchaseUrl());
    }


    private function isValidSignature(Setting $setting)
    {
        $credential = $setting->getCredential();

        $hashParams = $this->getHashParams();

        $hashParamsList = explode(':', $hashParams);

        $hashString = "";

        foreach ($hashParamsList as $hashParamName) {
            $hashString .= $this->getParameterByName($hashParamName);
        }

        $hashString .= $credential->getStoreKey();

        $cryptedHash = Helper::get3DCryptedHash($hashString);

        if ($cryptedHash === $this->getHash() && ($hashString === $this->getHashParamsVal() . $credential->getStoreKey())) {
            return true;
        }

        return false;
    }

    private function getParameterByName($name)
    {
        switch ($name) {
            case 'clientid':
                return $this->getClientId();
                break;
            case 'oid':
                return $this->getOrderId();
                break;
            case 'AuthCode':
                return $this->getAuthCode();
                break;
            case 'ProcReturnCode':
                return $this->getProcReturnCode();
                break;
            case 'Response':
                return $this->getResponse();
                break;
            case 'mdStatus':
                return $this->getMdStatus();
                break;
            case 'cavv':
                return $this->getCavv();
                break;
            case 'eci':
                return $this->getEci();
                break;
            case 'md':
                return $this->getMd();
                break;
            case 'rnd':
                return $this->getRnd();
                break;
        }
    }
}
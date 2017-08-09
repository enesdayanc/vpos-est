<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 09/08/2017
 * Time: 11:53
 */

namespace VPosEst\Model;


use VPosEst\Constant\MdStatus;
use VPosEst\Constant\Success;
use VPosEst\Helper\Helper;
use VPosEst\Response\Response;
use VPosEst\Setting\Credential;
use VPosEst\Setting\Setting;

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
     * @return Response
     */
    public function getResponseClass(Setting $setting)
    {
        $setting->validate();

        $validSignature = $this->isValidSignature($setting);

        $responseClass = new Response();

        $responseClass->setCode($this->getAuthCode());
        $responseClass->setTransactionReference($this->getTransId());

        if ($validSignature) {

            if (
                in_array($this->getMdStatus(), $this->allowedMdStatus)
                && ($this->getProcReturnCode() === Success::PROC_RETURN_CODE || $this->getResponse() === Success::RESPONSE)
            ) {
                $responseClass->setIsSuccessFul(true);
            }
        } else {
            $responseClass->setErrorMessage('Invalid Signature');
        }

        return $responseClass;
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
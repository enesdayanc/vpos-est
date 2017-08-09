<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 14:29
 */

namespace VPosEst\Setting;

use VPosEst\Helper\Validator;

abstract class Setting
{
    /** @var  Credential $credential */
    private $credential;
    private $threeDSuccessUrl;
    private $threeDFailUrl;

    /**
     * @return Credential
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param Credential $credential
     */
    public function setCredential(Credential $credential)
    {
        $this->credential = $credential;
    }

    /**
     * @return mixed
     */
    public function getThreeDSuccessUrl()
    {
        return $this->threeDSuccessUrl;
    }

    /**
     * @param mixed $threeDSuccessUrl
     */
    public function setThreeDSuccessUrl($threeDSuccessUrl)
    {
        $this->threeDSuccessUrl = $threeDSuccessUrl;
    }

    /**
     * @return mixed
     */
    public function getThreeDFailUrl()
    {
        return $this->threeDFailUrl;
    }

    /**
     * @param mixed $threeDFailUrl
     */
    public function setThreeDFailUrl($threeDFailUrl)
    {
        $this->threeDFailUrl = $threeDFailUrl;
    }

    public function validate()
    {
        Validator::validateNotEmpty('credential', $this->getCredential());
        $this->getCredential()->validate();
        Validator::validateNotEmpty('authorizeUrl', $this->getAuthorizeUrl());
        Validator::validateNotEmpty('captureUrl', $this->getCaptureUrl());
        Validator::validateNotEmpty('voidUrl', $this->getVoidUrl());
        Validator::validateNotEmpty('refundUrl', $this->getRefundUrl());
        Validator::validateNotEmpty('purchaseUrl', $this->getPurchaseUrl());
        Validator::validateNotEmpty('threeDPostUrl', $this->getThreeDPostUrl());
        Validator::validateNotEmpty('threeDFailUrl', $this->getThreeDFailUrl());
        Validator::validateNotEmpty('threeDSuccessUrl', $this->getThreeDSuccessUrl());
    }

    public abstract function getThreeDPostUrl();

    public abstract function getAuthorizeUrl();

    public abstract function getCaptureUrl();

    public abstract function getPurchaseUrl();

    public abstract function getRefundUrl();

    public abstract function getVoidUrl();
}
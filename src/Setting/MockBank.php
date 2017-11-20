<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 20.11.2017
 * Time: 13:42
 */

namespace PaymentGateway\VPosEst\Setting;


class MockBank extends Setting
{
    private $host;

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    public function getThreeDPostUrl()
    {
        return $this->getHost() . "/three-d-post";
    }

    public function getAuthorizeUrl()
    {
        return $this->getHost() . "/authorize";
    }

    public function getCaptureUrl()
    {
        return $this->getHost() . "/capture";
    }

    public function getPurchaseUrl()
    {
        return $this->getHost() . "/purchase";
    }

    public function getRefundUrl()
    {
        return $this->getHost() . "/refund";
    }

    public function getVoidUrl()
    {
        return $this->getHost() . "/void";
    }
}
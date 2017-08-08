<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 14:39
 */

namespace VPosEst\Setting;


class TurkiyeIsBankasiTest extends Setting
{
    /**
     * TurkiyeIsBankasiTest constructor.
     */
    public function __construct()
    {
        $credential = new Credential();

        $credential->setUsername('ISBANKAPI');
        $credential->setPassword('ISBANK07');
        $credential->setClientId('700655000200');
        $credential->setStoreKey('TRPS1234');

        parent::setCredential($credential);
    }

    public function getThreeDPostUrl()
    {
        return 'https://entegrasyon.asseco-see.com.tr/fim/est3Dgate';
    }

    public function getAuthorizeUrl()
    {
        return 'https://entegrasyon.asseco-see.com.tr/fim/api';
    }

    public function getCaptureUrl()
    {
        return 'https://entegrasyon.asseco-see.com.tr/fim/api';
    }

    public function getPurchaseUrl()
    {
        return 'https://entegrasyon.asseco-see.com.tr/fim/api';
    }

    public function getRefundUrl()
    {
        return 'https://entegrasyon.asseco-see.com.tr/fim/api';
    }

    public function getVoidUrl()
    {
        return 'https://entegrasyon.asseco-see.com.tr/fim/api';
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 14:39
 */

namespace PaymentGateway\VPosEst\Setting;


use PaymentGateway\VPosEst\Constant\StoreType;
use PaymentGateway\VPosEst\Exception\NotFoundException;

class TurkiyeIsBankasiTest extends Setting
{

    /**
     * TurkiyeIsBankasiTest constructor.
     * @param $storeType
     * @throws NotFoundException
     * @internal param $storeType
     */
    public function __construct($storeType)
    {
        $credential = new Credential();

        $credential->setUsername('ISBANKAPI');
        $credential->setPassword('ISBANK07');
        $credential->setStoreKey('TRPS1234');

        if ($storeType == StoreType::THREE_D_PAY) {
            $credential->setClientId('700655000200');
        } elseif ($storeType == StoreType::THREE_D) {
            $credential->setClientId('700655000100');
        } else {
            throw new NotFoundException('Client id no found for store type: ' . $storeType, 'CLIENT_ID_NOT_FOUND');
        }

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
<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 14:22
 */

namespace PaymentGateway\VPosEst\Helper;


use Exception;
use PaymentGateway\VPosEst\Constant\BankType;
use PaymentGateway\VPosEst\Exception\NotFoundException;
use PaymentGateway\VPosEst\Setting\AkBank;
use PaymentGateway\VPosEst\Setting\Finansbank;
use PaymentGateway\VPosEst\Setting\Setting;
use PaymentGateway\VPosEst\Setting\TurkEkonomiBankasi;
use PaymentGateway\VPosEst\Setting\TurkiyeIsBankasi;
use PaymentGateway\VPosEst\Setting\TurkiyeIsBankasiTest;
use SimpleXMLElement;
use PaymentGateway\VPosEst\Constant\Success;
use PaymentGateway\VPosEst\Exception\ValidationException;
use ReflectionClass;
use PaymentGateway\VPosEst\Response\Response;
use Spatie\ArrayToXml\ArrayToXml;

class Helper
{
    public static function getFormattedExpiryMonthYear($expiry)
    {
        if (empty($expiry)) {
            return null;
        }

        $expiry = strval($expiry);

        if (strlen($expiry) > 2) {
            $expiry = substr($expiry, -2);
        }

        return str_pad($expiry, 2, "0", STR_PAD_LEFT);
    }

    public static function getFormattedAmount($amount)
    {
        return number_format($amount, 2, '.', '');
    }

    public static function arrayToXmlString(array $array)
    {
        return ArrayToXml::convert($array, 'CC5Request');
    }

    public static function getConstants($class)
    {
        $oClass = new ReflectionClass ($class);
        return $oClass->getConstants();
    }

    public static function get3DHashString($clientId, $orderId, $amount, $threeDSuccessUrl, $threeDFailUrl, $type, $installment, $rnd, $storeKey)
    {
        return $clientId . $orderId . $amount . $threeDSuccessUrl . $threeDFailUrl . $type . $installment . $rnd . $storeKey;
    }

    public static function get3DCryptedHash($threeDHashString)
    {
        return base64_encode(pack('H*', sha1($threeDHashString)));
    }

    public static function getResponseByXML($xml)
    {
        $response = new Response();

        $response->setRawData($xml);

        try {
            $data = new SimpleXMLElement($xml);
        } catch (Exception $exception) {
            throw new ValidationException('Invalid Xml Response', 'INVALID_XML_RESPONSE');
        }

        if ((!empty($data->ProcReturnCode) && (string)$data->ProcReturnCode === Success::PROC_RETURN_CODE)
            || (!empty($data->Response) && $data->Response === Success::RESPONSE)) {
            $response->setSuccessful(true);
        }

        if (!empty($data->AuthCode)) {
            $response->setCode((string)$data->AuthCode);
        }

        if (!empty($data->Extra->ERRORCODE)) {
            $response->setErrorCode((string)$data->Extra->ERRORCODE);
        }

        if (!empty($data->ErrMsg)) {
            $response->setErrorMessage((string)$data->ErrMsg);
        }

        if (!empty($data->TransId)) {
            $response->setTransactionReference((string)$data->TransId);
        }

        return $response;
    }

    public static function amountParser($amount)
    {
        return $amount;
        return (int)number_format($amount, 2, '', '');
    }

    /**
     * @param $bankType
     * @param $storeType
     * @param bool $useTestCredential
     * @return AkBank|Finansbank|TurkEkonomiBankasi|TurkiyeIsBankasi|TurkiyeIsBankasiTest
     * @throws NotFoundException
     */
    public static function getSettingClassByBankTypeAndStoreType($bankType, $storeType, $useTestCredential = false)
    {
        Validator::validateBankType($bankType);
        Validator::validateStoreType($storeType);

        if ($useTestCredential) {
            $setting = new TurkiyeIsBankasiTest($storeType);
        } else {
            switch ($bankType) {
                case BankType::AKBANK:
                    $setting = new AkBank();
                    break;
                case BankType::FINANSBANK:
                    $setting = new Finansbank();
                    break;
                case BankType::TURK_EKONOMI_BANKASI:
                    $setting = new TurkEkonomiBankasi();
                    break;
                case BankType::TURKIYE_IS_BANKASI:
                    $setting = new TurkiyeIsBankasi();
                    break;
            }
        }

        if (!isset($setting) || !$setting instanceof Setting) {
            $userMessage = $bankType . ' not found';
            $internalMessage = 'BANK_TYPE_NOT_FOUND';
            throw new NotFoundException($userMessage, $internalMessage);
        }

        return $setting;
    }
}
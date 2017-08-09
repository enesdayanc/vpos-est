<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 14:22
 */

namespace VPosEst\Helper;


use Exception;
use SimpleXMLElement;
use VPosEst\Constant;
use VPosEst\Constant\Currency;
use VPosEst\Constant\Success;
use VPosEst\Exception\ValidationException;
use ReflectionClass;
use VPosEst\Response\Response;
use VPosEst\Setting\Setting;

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
        $document = new XMLBuilder();

        $domElements = $document->createElementsWithTextNodes($array);
        $document->appendListOfElementsToElement($document->root(), $domElements);
        return $document->saveXML();
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
            $response->setIsSuccessFul(true);
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
}
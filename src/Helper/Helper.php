<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 14:22
 */

namespace VPosEst\Helper;


use VPosEst\Constant;
use VPosEst\Constant\Currency;
use VPosEst\Constant\CurrencyCode;
use VPosEst\Exception\ValidationException;
use ReflectionClass;

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
}
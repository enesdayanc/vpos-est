<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 17:37
 */

namespace VPosEst\Helper;


use VPosEst\Constant\Currency;
use VPosEst\Constant\CurrencyCode;
use VPosEst\Constant\Language;
use VPosEst\Constant\RequestMode;
use VPosEst\Exception\ValidationException;
use ReflectionClass;

class Validator
{
    public static function validateCurrency($value)
    {
        if (!in_array($value, Helper::getConstants(Currency::class))) {
            throw new ValidationException('Invalid Currency', 'INVALID_CURRENCY');
        }
    }

    public static function validateCurrencyCode($value)
    {
        if (!in_array($value, Helper::getConstants(CurrencyCode::class))) {
            throw new ValidationException('Invalid Currency Code', 'INVALID_CURRENCY_CODE');
        }
    }

    public static function validateExpiryMonth($value)
    {
        if (!is_string($value) || strlen($value) != 2) {
            throw new ValidationException('Invalid Expiry Month', 'INVALID_EXPIRY_MONTH');
        }
    }

    public static function validateExpiryYear($value)
    {
        if (!is_string($value) || strlen($value) != 2) {
            throw new ValidationException('Invalid Expiry Year', 'INVALID_EXPIRY_YEAR');
        }
    }

    public static function validateCardNumber($value)
    {
        $number = preg_replace('/\D/', '', $value);
        $number_length = strlen($number);
        $parity = $number_length % 2;

        $total = 0;
        for ($i = 0; $i < $number_length; $i++) {
            $digit = $number[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $total += $digit;
        }

        if (!($total % 10 == 0)) {
            throw new ValidationException('Invalid Card Number', 'INVALID_CARD_NUMBER');
        }
    }

    public static function validateCvv($value)
    {
        if (!is_string($value) || !in_array(strlen($value), array(3, 4))) {
            throw new ValidationException('Invalid Cvv', 'INVALID_CVV');
        }
    }

    public static function validateRequestMode($value)
    {
        if (!in_array($value, Helper::getConstants(RequestMode::class))) {
            throw new ValidationException('Invalid Request Mode', 'INVALID_REQUEST_MODE');
        }
    }

    public static function validateOrderId($value)
    {
        if (empty($value)) {
            throw new ValidationException('Invalid Order Id', 'INVALID_ORDER_ID');
        }
    }

    public static function validateUserId($value)
    {
        if (empty($value)) {
            throw new ValidationException('Invalid User Id', 'INVALID_USER_ID');
        }
    }


    public static function validateInstallment($value)
    {
        if (empty($value) || !is_int($value)) {
            throw new ValidationException('Invalid Installment', 'INVALID_INSTALLMENT');
        }
    }

    public static function validateAmount($value)
    {
        if (!is_numeric($value)) {
            throw new ValidationException('Invalid Amount', 'INVALID_AMOUNT');
        }
    }

    public static function validateEmail($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Invalid Email', 'INVALID_EMAIL');
        }
    }

    public static function validateIp($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_IP)) {
            throw new ValidationException('Invalid Ip', 'INVALID_IP');
        }
    }

    public static function validateLanguage($value)
    {
        if (!in_array($value, Helper::getConstants(Language::class))) {
            throw new ValidationException('Invalid Language', 'INVALID_LANGUAGE');
        }
    }
}
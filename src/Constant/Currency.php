<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 16:29
 */

namespace VPosEst\Constant;

class Currency
{
    const TL = 'TRY';
    const USD = 'USD';
    const EUR = 'EUR';
    const GBP = 'GBP';

    public static $toCurrencyCode = array(
        self::TL => CurrencyCode::TL,
        self::USD => CurrencyCode::USD,
        self::EUR => CurrencyCode::EUR,
        self::GBP => CurrencyCode::GBP,
    );
}
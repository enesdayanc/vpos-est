<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 08/08/2017
 * Time: 16:30
 */

namespace VPosEst\Constant;

class CurrencyCode
{
    const TL = 949;
    const USD = 840;
    const EUR = 978;
    const GBP = 826;

    public static $toCurrency = array(
        self::TL => Currency::TL,
        self::USD => Currency::USD,
        self::EUR => Currency::EUR,
        self::GBP => Currency::GBP,
    );
}
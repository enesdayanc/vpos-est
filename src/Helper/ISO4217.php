<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 09/08/2017
 * Time: 09:10
 */

namespace VPosEst\Helper;


use Exception;
use VPosEst\Exception\NotFoundException;
use VPosEst\Exception\ValidationException;
use VPosEst\Model\ISO4217Country;
use VPosEst\Model\ISO4217Currency;

class ISO4217 extends \Alcohol\ISO4217
{
    /**
     * @param string $alpha3
     * @return ISO4217Currency
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getByAlpha3($alpha3)
    {
        try {
            $resultArray = parent::getByAlpha3($alpha3);
        } catch (\DomainException $exception) {
            throw new ValidationException($exception->getMessage(), 'INVALID_ALPHA3');
        } catch (\OutOfBoundsException $exception) {
            throw new NotFoundException($exception->getMessage(), 'ALPHA3_NOT_FOUND');
        }


        return $this->getObjectFromArray($resultArray);
    }

    /**
     * @param string $code
     * @return ISO4217Currency
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getByCode($code)
    {
        try {
            $resultArray = parent::getByCode($code);
        } catch (\DomainException $exception) {
            throw new ValidationException($exception->getMessage(), 'INVALID_CODE');
        } catch (\OutOfBoundsException $exception) {
            throw new NotFoundException($exception->getMessage(), 'CODE_NOT_FOUND');
        }

        return $this->getObjectFromArray($resultArray);
    }

    /**
     * @param string $numeric
     * @return ISO4217Currency
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getByNumeric($numeric)
    {
        try {
            $resultArray = parent::getByNumeric($numeric);
        } catch (\DomainException $exception) {
            throw new ValidationException($exception->getMessage(), 'INVALID_NUMERIC');
        } catch (\OutOfBoundsException $exception) {
            throw new NotFoundException($exception->getMessage(), 'NUMERIC_NOT_FOUND');
        }

        return $this->getObjectFromArray($resultArray);
    }

    /**
     * @return ISO4217Currency[]
     */
    public function getAll()
    {
        $resultArrayList = parent::getAll();

        $result = array();

        foreach ($resultArrayList as $resultArray) {
            $result[] = $this->getObjectFromArray($resultArray);
        }

        return $result;
    }

    /**
     * @param $resultArray
     * @return ISO4217Currency
     */
    private function getObjectFromArray($resultArray)
    {
        $result = new ISO4217Currency();

        $result->setName($resultArray['name']);
        $result->setAlpha3($resultArray['alpha3']);
        $result->setNumeric($resultArray['numeric']);
        $result->setExp($resultArray['exp']);

        if (is_array($resultArray['country'])) {
            foreach ($resultArray['country'] as $countryName) {
                $isoCountry = new ISO4217Country();
                $isoCountry->setName($countryName);
                $result->addCountry($isoCountry);
            }
        } else {
            $isoCountry = new ISO4217Country();
            $isoCountry->setName($resultArray['country']);
            $result->addCountry($isoCountry);
        }

        return $result;
    }


}
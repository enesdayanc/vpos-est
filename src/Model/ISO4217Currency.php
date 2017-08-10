<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 09/08/2017
 * Time: 09:12
 */

namespace Enesdayanc\VPosEst\Model;


class ISO4217Currency
{
    private $name;
    private $alpha3;
    private $numeric;
    private $exp;
    /** @var  ISO4217Country[] $countries */
    private $countries;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAlpha3()
    {
        return $this->alpha3;
    }

    /**
     * @param mixed $alpha3
     */
    public function setAlpha3($alpha3)
    {
        $this->alpha3 = $alpha3;
    }

    /**
     * @return mixed
     */
    public function getNumeric()
    {
        return $this->numeric;
    }

    /**
     * @param mixed $numeric
     */
    public function setNumeric($numeric)
    {
        $this->numeric = $numeric;
    }

    /**
     * @return mixed
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * @param mixed $exp
     */
    public function setExp($exp)
    {
        $this->exp = $exp;
    }

    /**
     * @return ISO4217Country[]
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param ISO4217Country $country
     */
    public function addCountry(ISO4217Country $country)
    {
        $this->countries[] = $country;
    }
}
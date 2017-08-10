<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 09/08/2017
 * Time: 09:14
 */

namespace Enesdayanc\VPosEst\Model;


class ISO4217Country
{
    private $name;

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
}
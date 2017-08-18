<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 07/08/2017
 * Time: 17:06
 */

namespace PaymentGateway\VPosEst\Setting;


use PaymentGateway\VPosEst\Helper\Validator;

class Credential
{
    private $username;
    private $password;
    private $clientId;
    private $storeKey;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getStoreKey()
    {
        return $this->storeKey;
    }

    /**
     * @param mixed $storeKey
     */
    public function setStoreKey($storeKey)
    {
        $this->storeKey = $storeKey;
    }

    public function validate()
    {
        Validator::validateNotEmpty('username', $this->getUsername());
        Validator::validateNotEmpty('password', $this->getPassword());
        Validator::validateNotEmpty('clientId', $this->getClientId());
    }
}
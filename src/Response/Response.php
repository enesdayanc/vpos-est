<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 03/08/2017
 * Time: 11:00
 */

namespace VPosEst\Response;

use VPosEst\Exception\ValidationException;
use VPosEst\Model\RedirectForm;
use SimpleXMLElement;

class Response
{

    const SUCCESS_PROC_RETURN_CODE = '00';
    const SUCCESS_RESPONSE = 'Approved';

    protected $data;
    protected $rawResponse;
    /** @var RedirectForm $redirectForm */
    private $redirectForm;

    public function __construct($rawResponse, RedirectForm $redirectForm)
    {
        $this->redirectForm = $redirectForm;

        if (!empty($rawResponse) && is_string($rawResponse)) {
            try {
                $this->rawResponse = $rawResponse;
                $this->data = new SimpleXMLElement($rawResponse);
            } catch (\Exception $ex) {
                throw new ValidationException('Invalid Response', 'INVALID_RESPONSE');
            }
        } else if (is_array($rawResponse)) {
            $this->data = (object)$rawResponse;
            $this->rawResponse = json_encode($rawResponse, true);
        } else if (is_object($rawResponse)) {
            $this->data = $rawResponse;
            $this->rawResponse = json_encode($rawResponse, true);
        } else {
            $this->data = $rawResponse;
            $this->rawResponse = $rawResponse;
        }
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function isSuccessful()
    {
        if (
            (!empty($this->data->ProcReturnCode) && (string)$this->data->ProcReturnCode === self::SUCCESS_PROC_RETURN_CODE)
            || (!empty($this->data->Response) && $this->data->Response === self::SUCCESS_RESPONSE)
        ) {
            return true;
        }
        return false;
    }

    public function getCode()
    {
        if (!empty($this->data->AuthCode)) {
            return $this->data->AuthCode;
        }
        return null;
    }

    public function getErrorCode()
    {
        if (!empty($this->data->Extra->ERRORCODE)) {
            return $this->data->Extra->ERRORCODE;
        }
        return null;
    }

    public function getErrorMessage()
    {
        if (!empty($this->data->ErrMsg)) {
            return $this->data->ErrMsg;
        }
        return null;
    }

    public function getTransactionReference()
    {
        if (!empty($this->data->TransId)) {
            return $this->data->TransId;
        }
        return null;
    }

    public function isRedirect()
    {
        if (!empty($this->redirectForm->getAction())) {
            return true;
        }
        return false;
    }

    public function getRedirectUrl()
    {
        return $this->redirectForm->getAction();
    }

    public function getRedirectMethod()
    {
        return $this->redirectForm->getMethod();
    }

    public function getRedirectData()
    {
        return $this->redirectForm->getParameters();
    }
}
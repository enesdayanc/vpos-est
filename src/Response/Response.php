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
        $this->rawResponse = $rawResponse;
        $this->redirectForm = $redirectForm;

        if (!empty($rawResponse) && !is_array($rawResponse)) {
            try {
                $this->data = new SimpleXMLElement($rawResponse);
            } catch (\Exception $ex) {
                throw new ValidationException('Invalid Response', 'INVALID_RESPONSE');
            }
        } else if (is_array($rawResponse)) {
            $this->data = $rawResponse;
        }
    }

    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    public function isSuccessful()
    {
        if (
            (isset($this->data->ProcReturnCode) && (string)$this->data->ProcReturnCode === self::SUCCESS_PROC_RETURN_CODE)
            || (isset($this->data->Response) && $this->data->Response === self::SUCCESS_RESPONSE)
        ) {
            return true;
        }
        return false;
    }

    public function getCode()
    {
        return $this->data->AuthCode;
    }

    public function getErrorCode()
    {
        return $this->data->Extra->ERRORCODE;
    }

    public function getErrorMessage()
    {
        return $this->data->ErrMsg;
    }

    public function getTransactionReference()
    {
        return $this->data->TransId;
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
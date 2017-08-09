<?php

namespace VPosEst;

use Exception;
use GuzzleHttp\Client;
use VPosEst\Constant\RedirectFormMethod;
use VPosEst\Constant\StoreType;
use VPosEst\Helper\Helper;
use VPosEst\Model\Card;
use VPosEst\Model\RedirectForm;
use VPosEst\Model\ThreeDResponse;
use VPosEst\Request\AuthorizeRequest;
use VPosEst\Request\CaptureRequest;
use VPosEst\Request\PurchaseRequest;
use VPosEst\Request\RefundRequest;
use VPosEst\Request\RequestInterface;
use VPosEst\Request\VoidRequest;
use VPosEst\Response\Response;
use VPosEst\Setting\Setting;

class VPos
{
    /** @var  Setting $setting */
    private $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
        $this->setting->validate();
    }

    public function authorize(AuthorizeRequest $authorizeRequest)
    {
        return $this->send($authorizeRequest, $this->setting->getAuthorizeUrl());
    }

    public function capture(CaptureRequest $captureRequest)
    {
        return $this->send($captureRequest, $this->setting->getCaptureUrl());
    }

    public function purchase(PurchaseRequest $purchaseRequest)
    {
        return $this->send($purchaseRequest, $this->setting->getPurchaseUrl());
    }

    public function refund(RefundRequest $refundRequest)
    {
        return $this->send($refundRequest, $this->setting->getRefundUrl());
    }

    public function void(VoidRequest $voidRequest)
    {
        return $this->send($voidRequest, $this->setting->getVoidUrl());
    }

    private function send(RequestInterface $requestElements, $url)
    {
        $documentString = $requestElements->toXmlString($this->setting->getCredential());

        $client = new Client();

        try {
            $clientResponse = $client->post($url, [
                'form_params' => [
                    'DATA' => $documentString,
                ]
            ]);
        } catch (Exception $exception) {
            throw new Exception('Guzzle Error');
        }

        return Helper::getResponseByXML($clientResponse->getBody()->getContents());
    }

    public function authorize3D(AuthorizeRequest $authorizeRequest)
    {

        $redirectForm = $authorizeRequest->get3DRedirectForm($this->setting);

        $response = new Response();

        $response->setIsRedirect(true);
        $response->setRedirectMethod($redirectForm->getMethod());
        $response->setRedirectUrl($redirectForm->getAction());
        $response->setRedirectData($redirectForm->getParameters());

        return $response;
    }

    public function purchase3D(PurchaseRequest $purchaseRequest)
    {
        $redirectForm = $purchaseRequest->get3DRedirectForm($this->setting);

        $response = new Response();

        $response->setIsRedirect(true);
        $response->setRedirectMethod($redirectForm->getMethod());
        $response->setRedirectUrl($redirectForm->getAction());
        $response->setRedirectData($redirectForm->getParameters());

        return $response;
    }

    public function handle3DResponse(ThreeDResponse $threeDResponse)
    {
        return $threeDResponse->getResponseClass($this->setting);
    }
}
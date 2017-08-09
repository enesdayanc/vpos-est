<?php

namespace VPosEst;

use Exception;
use GuzzleHttp\Client;
use VPosEst\Constant\RedirectFormMethod;
use VPosEst\Constant\StoreType;
use VPosEst\Helper\Helper;
use VPosEst\Model\Card;
use VPosEst\Model\RedirectForm;
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

        return new Response($clientResponse->getBody()->getContents(), new RedirectForm());
    }

    public function authorize3D(AuthorizeRequest $authorizeRequest)
    {
        return new Response(null, $authorizeRequest->get3DRedirectForm($this->setting));
    }

    public function purchase3D(PurchaseRequest $purchaseRequest)
    {
        return new Response(null, $purchaseRequest->get3DRedirectForm($this->setting));
    }
}
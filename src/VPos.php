<?php

namespace PaymentGateway\VPosEst;

use Exception;
use GuzzleHttp\Client;
use PaymentGateway\VPosEst\Constant\RedirectFormMethod;
use PaymentGateway\VPosEst\Constant\StoreType;
use PaymentGateway\VPosEst\Exception\CurlException;
use PaymentGateway\VPosEst\Helper\Helper;
use PaymentGateway\VPosEst\Model\Card;
use PaymentGateway\VPosEst\Model\RedirectForm;
use PaymentGateway\VPosEst\Model\ThreeDResponse;
use PaymentGateway\VPosEst\Request\AuthorizeRequest;
use PaymentGateway\VPosEst\Request\CaptureRequest;
use PaymentGateway\VPosEst\Request\PurchaseRequest;
use PaymentGateway\VPosEst\Request\RefundRequest;
use PaymentGateway\VPosEst\Request\RequestInterface;
use PaymentGateway\VPosEst\Request\VoidRequest;
use PaymentGateway\VPosEst\Response\Response;
use PaymentGateway\VPosEst\Setting\Setting;

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

    /**
     * @param RequestInterface $requestElements
     * @param $url
     * @return Response
     */
    private function send(RequestInterface $requestElements, $url)
    {
        $httpClient = new HttpClient($this->setting);

        return $httpClient->send($requestElements, $url);
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

    public function handle3DResponse(ThreeDResponse $threeDResponse, $orderId)
    {
        return $threeDResponse->getResponseClass($this->setting, $orderId);
    }

    /**
     * @return Setting
     */
    public function getSetting(): Setting
    {
        return $this->setting;
    }
}
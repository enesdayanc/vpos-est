<?php

namespace Enesdayanc\VPosEst;

use Exception;
use GuzzleHttp\Client;
use Enesdayanc\VPosEst\Constant\RedirectFormMethod;
use Enesdayanc\VPosEst\Constant\StoreType;
use Enesdayanc\VPosEst\Exception\CurlException;
use Enesdayanc\VPosEst\Helper\Helper;
use Enesdayanc\VPosEst\Model\Card;
use Enesdayanc\VPosEst\Model\RedirectForm;
use Enesdayanc\VPosEst\Model\ThreeDResponse;
use Enesdayanc\VPosEst\Request\AuthorizeRequest;
use Enesdayanc\VPosEst\Request\CaptureRequest;
use Enesdayanc\VPosEst\Request\PurchaseRequest;
use Enesdayanc\VPosEst\Request\RefundRequest;
use Enesdayanc\VPosEst\Request\RequestInterface;
use Enesdayanc\VPosEst\Request\VoidRequest;
use Enesdayanc\VPosEst\Response\Response;
use Enesdayanc\VPosEst\Setting\Setting;

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

    public function handle3DResponse(ThreeDResponse $threeDResponse)
    {
        return $threeDResponse->getResponseClass($this->setting);
    }
}
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
        $orderId = $authorizeRequest->getOrderId();
        $card = $authorizeRequest->getCard();
        $installment = $authorizeRequest->getInstallment();
        $language = $authorizeRequest->getLanguage();
        $amount = $authorizeRequest->getAmount();
        $currency = $authorizeRequest->getCurrency();

        return $this->get3DForm($orderId, $installment, $amount, $card, "PreAuth", $language, $currency);
    }

    public function purchase3D(PurchaseRequest $purchaseRequest)
    {
        $orderId = $purchaseRequest->getOrderId();
        $card = $purchaseRequest->getCard();
        $installment = $purchaseRequest->getInstallment();
        $language = $purchaseRequest->getLanguage();
        $amount = $purchaseRequest->getAmount();
        $currency = $purchaseRequest->getCurrency();

        return $this->get3DForm($orderId, $installment, $amount, $card, "Auth", $language, $currency);
    }

    private function get3DForm($orderId, $installment, $amount, Card $card, $type, $language, $currency)
    {
        $amount = Helper::getFormattedAmount($amount);
        $currencyCode = Helper::getCurrencyCodeByCurrency($currency);

        $rnd = md5(microtime());

        $params = array(
            'pan' => $card->getCreditCardNumber(),
            'cv2' => $card->getCvv(),
            'Ecom_Payment_Card_ExpDate_Year' => $card->getExpiryYear(),
            'Ecom_Payment_Card_ExpDate_Month' => $card->getExpiryMonth(),
            'clientid' => $this->setting->getCredential()->getClientId(),
            'oid' => $orderId,
            'okUrl' => $this->setting->getThreeDSuccessUrl(),
            'failUrl' => $this->setting->getThreeDFailUrl(),
            'rnd' => $rnd,
            'islemtipi' => $type,
            'taksit' => $installment,
            'storetype' => StoreType::THREE_D_PAY,
            'lang' => $language,
            'hash' => $this->get3DHash($orderId, $amount, $type, $installment, $rnd),
            'amount' => $amount,
            'currency' => $currencyCode
        );

        $redirectForm = new RedirectForm();
        $redirectForm->setAction($this->setting->getThreeDPostUrl());
        $redirectForm->setMethod(RedirectFormMethod::POST);
        $redirectForm->setParameters($params);

        return new Response(null, $redirectForm);
    }

    private function get3DHash($orderId, $amount, $type, $installment, $rnd)
    {
        $hashstr = $this->setting->getCredential()->getClientId() . $orderId . $amount . $this->setting->getThreeDSuccessUrl() . $this->setting->getThreeDFailUrl() . $type . $installment . $rnd . $this->setting->getCredential()->getStoreKey();

        return base64_encode(pack('H*', sha1($hashstr)));
    }
}
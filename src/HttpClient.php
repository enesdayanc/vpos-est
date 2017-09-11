<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 16/08/2017
 * Time: 14:07
 */

namespace PaymentGateway\VPosEst;


use PaymentGateway\VPosEst\Exception\CurlException;
use PaymentGateway\VPosEst\Helper\Helper;
use PaymentGateway\VPosEst\Request\RequestInterface;
use PaymentGateway\VPosEst\Setting\Setting;
use Exception;
use GuzzleHttp\Client;

class HttpClient
{
    private $setting;
    private $timeout = 20;

    /**
     * HttpClient constructor.
     * @param $setting
     */
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }


    /**
     * @param RequestInterface $requestElements
     * @param $url
     * @return Response\Response
     * @throws CurlException
     */
    public function send(RequestInterface $requestElements, $url)
    {
        $documentString = $requestElements->toXmlString($this->setting->getCredential());

        $client = new Client();

        try {
            $clientResponse = $client->post($url, [
                'timeout' => $this->timeout,
                'form_params' => [
                    'DATA' => $documentString,
                ]
            ]);
        } catch (Exception $exception) {
            throw new CurlException('Connection Error', $exception->getMessage());
        }

        return Helper::getResponseByXML($clientResponse->getBody()->getContents(), $documentString);
    }
}
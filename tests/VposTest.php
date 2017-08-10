<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 09/08/2017
 * Time: 16:36
 */

namespace Enesdayanc\VPosEst;

use Enesdayanc\Iso4217\Iso4217;
use Enesdayanc\Iso4217\Model\Currency;
use Enesdayanc\VPosEst\Request\RefundRequest;
use Enesdayanc\VPosEst\Request\VoidRequest;
use PHPUnit\Framework\TestCase;
use Enesdayanc\VPosEst\Constant\Language;
use Enesdayanc\VPosEst\Constant\RequestMode;
use Enesdayanc\VPosEst\Model\Card;
use Enesdayanc\VPosEst\Request\AuthorizeRequest;
use Enesdayanc\VPosEst\Request\CaptureRequest;
use Enesdayanc\VPosEst\Request\PurchaseRequest;
use Enesdayanc\VPosEst\Response\Response;
use Enesdayanc\VPosEst\Setting\TurkiyeIsBankasiTest;

class VposTest extends TestCase
{
    /** @var  VPos $vPos */
    protected $vPos;
    /** @var  Card $card */
    protected $card;
    /** @var  Currency $currency */
    protected $currency;

    protected $orderId;
    protected $authorizeOrderId;
    protected $amount;
    protected $userId;
    protected $installment;


    public function setUp()
    {
        $settings = new TurkiyeIsBankasiTest();

        $settings->setThreeDFailUrl('http://test.fail');
        $settings->setThreeDSuccessUrl('http://test.success');

        $this->vPos = new VPos($settings);

        $card = new Card();
        $card->setCreditCardNumber("4508034508034509");
        $card->setExpiryMonth('12');
        $card->setExpiryYear('18');
        $card->setCvv('000');
        $card->setFirstName('Enes');
        $card->setLastName('Dayanç');

        $this->card = $card;

        $iso4217 = new Iso4217();

        $this->currency = $iso4217->getByCode('TRY');

        $this->amount = rand(1, 1000);
        $this->orderId = md5(microtime() . rand());
        $this->userId = md5(microtime() . rand());
        $this->installment = rand(1, 12);

    }

    public function testPurchase()
    {
        $purchaseRequest = new PurchaseRequest();

        $purchaseRequest->setCard($this->card);
        $purchaseRequest->setMode(RequestMode::P);
        $purchaseRequest->setOrderId($this->orderId);
        $purchaseRequest->setAmount($this->amount);
        $purchaseRequest->setCurrency($this->currency);
        $purchaseRequest->setLanguage(Language::TR);
        $purchaseRequest->setUserId($this->userId);
        $purchaseRequest->setInstallment($this->installment);
        $purchaseRequest->setIp('198.168.1.1');
        $purchaseRequest->setEmail('enes.dayanc@modanisa.com.tr');

        $response = $this->vPos->purchase($purchaseRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());

        return array(
            'orderId' => $this->orderId,
            'amount' => $this->amount,
        );
    }


    public function testPurchaseFail()
    {
        $purchaseRequest = new PurchaseRequest();

        $purchaseRequest->setCard($this->card);
        $purchaseRequest->setMode(RequestMode::P);
        $purchaseRequest->setOrderId($this->orderId);
        $purchaseRequest->setAmount(0);
        $purchaseRequest->setCurrency($this->currency);
        $purchaseRequest->setLanguage(Language::TR);
        $purchaseRequest->setUserId($this->userId);
        $purchaseRequest->setInstallment($this->installment);
        $purchaseRequest->setIp('198.168.1.1');
        $purchaseRequest->setEmail('enes.dayanc@modanisa.com.tr');

        $response = $this->vPos->purchase($purchaseRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('CORE-2009', $response->getErrorCode());
    }

    public function testAuthorize()
    {
        $authorizeRequest = new AuthorizeRequest();

        $authorizeRequest->setCard($this->card);
        $authorizeRequest->setMode(RequestMode::P);
        $authorizeRequest->setOrderId($this->orderId);
        $authorizeRequest->setAmount($this->amount);
        $authorizeRequest->setCurrency($this->currency);
        $authorizeRequest->setLanguage(Language::TR);
        $authorizeRequest->setUserId($this->userId);
        $authorizeRequest->setInstallment($this->installment);
        $authorizeRequest->setIp('198.168.1.1');
        $authorizeRequest->setEmail('enes.dayanc@modanisa.com.tr');

        $response = $this->vPos->authorize($authorizeRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());

        return array(
            'orderId' => $this->orderId,
            'amount' => $this->amount,
        );
    }

    public function testAuthorizeFail()
    {
        $authorizeRequest = new AuthorizeRequest();

        $authorizeRequest->setCard($this->card);
        $authorizeRequest->setMode(RequestMode::P);
        $authorizeRequest->setOrderId(1);
        $authorizeRequest->setAmount($this->amount);
        $authorizeRequest->setCurrency($this->currency);
        $authorizeRequest->setLanguage(Language::TR);
        $authorizeRequest->setUserId($this->userId);
        $authorizeRequest->setInstallment($this->installment);
        $authorizeRequest->setIp('198.168.1.1');
        $authorizeRequest->setEmail('enes.dayanc@modanisa.com.tr');

        $response = $this->vPos->authorize($authorizeRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('CORE-2507', $response->getErrorCode());
    }

    /**
     * @depends testAuthorize
     * @param $params
     */
    public function testCapture($params)
    {
        $captureRequest = new CaptureRequest();

        $captureRequest->setOrderId($params['orderId']);
        $captureRequest->setAmount($params['amount']);
        $captureRequest->setCurrency($this->currency);
        $captureRequest->setMode(RequestMode::P);

        $response = $this->vPos->capture($captureRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());
    }

    public function testCaptureFail()
    {
        $captureRequest = new CaptureRequest();

        $captureRequest->setOrderId(1);
        $captureRequest->setAmount($this->amount);
        $captureRequest->setCurrency($this->currency);
        $captureRequest->setMode(RequestMode::P);

        $response = $this->vPos->capture($captureRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('CORE-2509', $response->getErrorCode());
    }


    /**
     * @depends testPurchase
     * @param $params
     */
    public function testRefund($params)
    {
        $refundRequest = new RefundRequest();
        $refundRequest->setMode(RequestMode::P);
        $refundRequest->setCurrency($this->currency);
        $refundRequest->setAmount($params['amount'] / 2);
        $refundRequest->setOrderId($params['orderId']);

        $response = $this->vPos->refund($refundRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());

        return $params;
    }


    /**
     * @depends testRefund
     * @param $params
     */
    public function testRefundFail($params)
    {
        $refundRequest = new RefundRequest();
        $refundRequest->setMode(RequestMode::P);
        $refundRequest->setCurrency($this->currency);
        $refundRequest->setAmount($params['amount'] + 10);
        $refundRequest->setOrderId($params['orderId']);

        $response = $this->vPos->refund($refundRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('CORE-2503', $response->getErrorCode());
    }

    /**
     * @depends testPurchase
     * @param $params
     */
    public function testVoid($params)
    {
        $voidRequest = new VoidRequest();
        $voidRequest->setOrderId($params['orderId']);
        $voidRequest->setMode(RequestMode::P);

        $response = $this->vPos->void($voidRequest);


        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());
    }

    public function testVoidFail()
    {
        $voidRequest = new VoidRequest();
        $voidRequest->setOrderId(1);
        $voidRequest->setMode(RequestMode::P);

        $response = $this->vPos->void($voidRequest);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertFalse($response->isSuccessFul());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('CORE-2008', $response->getErrorCode());
    }

}
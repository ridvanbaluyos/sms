<?php
use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use ridvanbaluyos\sms\providers\PromoTexter as PromoTexter;
use ridvanbaluyos\sms\providers\RisingTide as RisingTide;
use ridvanbaluyos\sms\providers\Semaphore as Semaphore;
use ridvanbaluyos\sms\providers\Chikka as Chikka;
use ridvanbaluyos\sms\providers\Nexmo as Nexmo;
use ridvanbaluyos\sms\providers\Twilio as Twilio;

class SendSmsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $phoneNumber = '639989764990';
    protected $message = 'Unit Testing for SendSmsTest';

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    protected function sendSuccess(SmsProviderServicesInterface $provider)
    {
        $sms = new Sms($provider);
//        if ($sms->getSmsProviderName() != 'Chikka') return;

//        $response = $sms->send($this->phoneNumber, $this->message);
//
//        $this->assertJson($response);
//        $response = json_decode($response, true);
//
//        $this->assertArrayHasKey('data', $response);
//        $this->assertArrayHasKey('code', $response['data']);
//        $this->assertArrayHasKey('message', $response['data']);
//        $this->assertArrayHasKey('provider', $response['data']);
//        $this->assertArrayHasKey('metadata', $response['data']);
//
//        $this->assertTrue(is_int($response['data']['code']));
//        $this->assertEquals($sms->getSmsProviderName(), $response['data']['provider']);
//        $this->assertLessThanOrEqual(299, $response['data']['code']);
//        $this->assertGreaterThanOrEqual(200, $response['data']['code']);
//        $this->assertNotEmpty($response['data']['metadata']);
    }

    protected function sendFail(SmsProviderServicesInterface $provider)
    {
        $sms = new Sms($provider);
//        if ($sms->getSmsProviderName() != 'Chikka') return;

//        $response = $sms->send($this->phoneNumber, $this->message);
//
//        $this->assertJson($response);
//        $response = json_decode($response, true);
//
//        $this->assertArrayHasKey('error', $response);
//        $this->assertArrayHasKey('code', $response['error']);
//        $this->assertArrayHasKey('message', $response['error']);
//        $this->assertArrayHasKey('provider', $response['error']);
//        $this->assertArrayHasKey('metadata', $response['error']);
//
//        $this->assertTrue(is_int($response['error']['code']));
//        $this->assertEquals($sms->getSmsProviderName(), $response['error']['provider']);
//        $this->assertLessThanOrEqual(599, $response['error']['code']);
//        $this->assertGreaterThanOrEqual(400, $response['error']['code']);
//        $this->assertNotEmpty($response['error']['metadata']);
    }

    // Send via PromoTexter Success
    public function testSendViaPromoTexterSuccess()
    {
        $provider = new PromoTexter();
        $this->sendSuccess($provider);

    }

    // Send via PromoTexter Fail (Incorrect Phone Number Format)
    public function testSendViaPromoTexterFail()
    {
        $this->phoneNumber = 'AAA';
        $provider = new PromoTexter();
        $this->sendFail($provider);
    }

    // Send via RisingTide Success
    public function testSendViaRisingTideSuccess()
    {
        $provider = new RisingTide();
        $this->sendSuccess($provider);
    }

    // Send via RisingTide Fail
    public function testSendViaRisingTideFail()
    {
        $this->phoneNumber = 'AAA';
        $provider = new RisingTide();
        $this->sendFail($provider);
    }

    // Send via Semaphore Success
    public function testSendViaSemaphoreSuccess()
    {
        $provider = new Semaphore();
        $this->sendSuccess($provider);
    }

    // Send via Semaphore Fail
    public function testSendViaSemaphoreFail()
    {
        $this->phoneNumber = 'AAA';
        $provider = new Semaphore();
        $this->sendFail($provider);
    }

    // Send via Chikka Success
    public function testSendViaChikkaSuccess()
    {
        $provider = new Chikka();
        $this->sendSuccess($provider);
    }

    // Send via Chikka Fail
    public function testSendViaChikkaFail()
    {
        $this->phoneNumber = 'AAA';
        $provider = new Chikka();
        $this->sendFail($provider);
    }

    // Send via Chikka Success
    public function testSendViaNexmoSuccess()
    {
        $provider = new Nexmo();
        $this->sendSuccess($provider);
    }

    // Send via Nexmo Fail
    public function testSendViaNexmoFail()
    {
        $this->phoneNumber = 'AAA';
        $provider = new Nexmo();
        $this->sendFail($provider);
    }

    // Send via Twilio Success
    public function testSendViaTwilioSuccess()
    {
        $provider = new Twilio();
        $this->sendSuccess($provider);
    }

    // Send via Nexmo Fail
    public function testSendViaTwilioFail()
    {
        $this->phoneNumber = 'AAA';
        $provider = new Twilio();
        $this->sendFail($provider);
    }
}
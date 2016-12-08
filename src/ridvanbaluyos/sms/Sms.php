<?php
namespace ridvanbaluyos\sms;

use ridvanbaluyos\sms\SmsProvider as SmsProvider;

class Sms
{
	private $smsProvider;
	private $phoneNumber;
	private $message;
	
	public function __construct(SmsProviderServicesInterface $smsProvider = null)
	{
	    if (is_null($smsProvider)) {
            $this->randomizeProvider();
        }

		$this->smsProvider = $smsProvider;

	}
	
	public function send($phoneNumber, $message)
	{
		$this->smsProvider->send($phoneNumber, $message);
	}

	private function randomizeProvider()
    {

    }
}
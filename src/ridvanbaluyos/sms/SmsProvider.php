<?php
namespace ridvanbaluyos\sms;

class SmsProvider
{
	private $provider;
	
	public function __construct($provider)
	{
		$this->provider = $provider;
	}
	
	public function send($phoneNumber, $message)
	{
		$this->provider->send($phoneNumber, $message);
	}
}
<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface;

class RisingTide implements SmsProviderServicesInterface
{
	public function send($phoneNumber, $message)
	{
		echo "sent via rising tide";
	}
}
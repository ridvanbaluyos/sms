<?php
namespace ridvanbaluyos\sms;

interface SmsProviderServicesInterface
{
	public function send($phoneNumber, $message);
}
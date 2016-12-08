<?php
namespace ridvanbaluyos\sms;

/**
 * Interface SmsProviderServicesInterface
 * @package ridvanbaluyos\sms
 */
interface SmsProviderServicesInterface
{
	public function send($phoneNumber, $message);
}
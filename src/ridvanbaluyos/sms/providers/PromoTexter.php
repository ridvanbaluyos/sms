<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface;

class PromoTexter implements SmsProviderServicesInterface
{
	public function send($phoneNumber, $message)
	{
		echo 'sent via promotexter';
	}
}

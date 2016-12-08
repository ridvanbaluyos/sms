<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface;

class Semaphore implements SmsProviderServicesInterface
{
    public function send($phoneNumber, $message)
    {
        echo "sent via semaphore";
    }
}
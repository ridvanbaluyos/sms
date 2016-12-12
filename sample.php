<?php
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

use ridvanbaluyos\sms\Sms as Sms;
use ridvanbaluyos\sms\providers\PromoTexter as PromoTexter;
use ridvanbaluyos\sms\providers\RisingTide as RisingTide;
use ridvanbaluyos\sms\providers\Semaphore as Semaphore;
use ridvanbaluyos\sms\providers\Chikka as Chikka;
use ridvanbaluyos\sms\providers\Nexmo as Nexmo;
use ridvanbaluyos\sms\providers\Twilio as Twilio;

$message = 'this is a test message';
$phoneNumber = '639123456789';

// Just change the classname to either PromoTexter, RisingTide, Chikka, or Semaphore.
$provider = new Twilio();

// If no provider is passed, it will be randomized based on the weight distribution.
$sms = new Sms($provider);
$sms->send($phoneNumber, $message);

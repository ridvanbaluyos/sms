<?php
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

use ridvanbaluyos\sms\Sms as Sms;
use ridvanbaluyos\sms\providers\PromoTexter as PromoTexter;
use ridvanbaluyos\sms\providers\RisingTide as RisingTide;
use ridvanbaluyos\sms\providers\Semaphore as Semaphore;
use ridvanbaluyos\sms\providers\Chikka as Chikka;

$x = new PromoTexter();
$message = 'this is a test message';
$phoneNumber = '631234567890';

// Just change the classname to either PromoTexter, RisingTide, or Semaphore
$provider = new Chikka();
$sms = new Sms($provider);
$sms->send($phoneNumber, $message);
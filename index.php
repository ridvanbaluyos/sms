<?php
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';

use ridvanbaluyos\sms\Sms as Sms;
use ridvanbaluyos\sms\providers\PromoTexter as PromoTexter;
use ridvanbaluyos\sms\providers\RisingTide as RisingTide;
use ridvanbaluyos\sms\providers\Semaphore as Semaphore;

$x = new PromoTexter();
$message = 'hi';
$phoneNumber = '639989764990';

$provider = new Semaphore();
$sms = new Sms($provider);

$sms->send($phoneNumber, $message);
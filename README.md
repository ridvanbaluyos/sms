ridvanbaluyos/sms
=======

An SMS Provider Library for PHP 

[![Latest Stable Version](https://poser.pugx.org/ridvanbaluyos/sms/v/stable)](https://packagist.org/packages/ridvanbaluyos/sms)
[![Latest Unstable Version](https://poser.pugx.org/ridvanbaluyos/sms/v/unstable)](https://packagist.org/packages/ridvanbaluyos/sms)
[![Total Downloads](https://poser.pugx.org/ridvanbaluyos/sms/downloads)](https://packagist.org/packages/ridvanbaluyos/sms)
[![Build Status](https://scrutinizer-ci.com/g/ridvanbaluyos/sms-providers/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ridvanbaluyos/sms-providers/build-status/master)
[![License](https://poser.pugx.org/ridvanbaluyos/sms/license)](https://packagist.org/packages/ridvanbaluyos/sms)

- [Installation](#installation)
- [Supported SMS Providers](#supported-sms-providers)
- [Configuration](#configuration)
- [Usage](#usage)
    - [Sending SMS (with Provider)](#sending-sms-with-provider)
    - [Sending SMS (with No Provider)](#sending-sms-with-no-provider)
- [To Follow](#to-follow)
    
    
## Installation ##
Open your `composer.json` file and add the following to the `require` key:

    "ridvanbaluyos/sms": "v0.2-alpha"

After adding the key, run composer update from the command line to install the package

```bash
composer update
```

Or simply add:
```bash
composer require ridvanbaluyos/sms
```

## Supported SMS Providers ##
1. [Semaphore](http://semaphore.co/)
2. [PromoTexter](http://www.promotexter.com/)
3. [RisingTide](http://www.risingtide.ph/)
4. [Chikka](http://api.chikka.com/)

Make sure you register for an account and load up your balance. 

## Configuration ##
1. Go to `src/ridvanbaluyos/sms/config` folder.
2. Rename `default.providers.json` to `providers.json`.
3. Fill-up the necessary fields. You need not fill up all providers, only those that you are using.
```
  "Semaphore" : {
    "url" : "http://api.semaphore.co/api/sms",
    "from" : "Justin Bieber",
    "api" : "1$1++0074+3n0w+0$4ychUR1-cHUr1'x"
  },
```
4. In the same folder, open `distributions.json` and fill up the weights. The total value should be 1.0.
```
{
  "PromoTexter" : "0.5",
  "Semaphore" : "",
  "RisingTide" : "0.5",
  "Chikka" : ""
}
```

## Usage ##
### Sending SMS with Provider ###
```php
use ridvanbaluyos\sms\Sms as Sms;
use ridvanbaluyos\sms\providers\PromoTexter as PromoTexter;

$message = 'this is a test message';
$phoneNumber = '639123456789';

$provider = new PromoTexter();
$sms = new Sms($provider);
$sms->send($phoneNumber, $message);
```
### Sending SMS with No Provider ###
```php
use ridvanbaluyos\sms\Sms as Sms;

$message = 'this is a test message';
$phoneNumber = '639123456789';

$sms = new Sms();
$sms->send($phoneNumber, $message);
```
>When no SMS provider is specified, it will be randomized based on the weights that you defined in the `distribution.json` file (eg. 0.25 is 25% chance).

## To Follow ##
1. [Unit Testing](http://codeception.com/)
2. More SMS Providers!

<?php
namespace ridvanbaluyos\sms;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\providers\PromoTexter;
use ridvanbaluyos\sms\providers\RisingTide;
use ridvanbaluyos\sms\providers\Semaphore;
use ridvanbaluyos\sms\providers\Chikka;
use Noodlehaus\Config as Config;

/**
 * Class Sms
 * @package ridvanbaluyos\sms
 */
class Sms implements SmsProviderServicesInterface
{
    /**
     * @var SmsProviderServicesInterface
     */
    private $smsProvider;

    /**
     * Sms constructor.
     * @param SmsProviderServicesInterface|null $smsProvider
     */
    public function __construct(SmsProviderServicesInterface $smsProvider = null)
    {
        // If no provider is specified, randomize by distribution percentage.
        if ($smsProvider instanceof SmsProviderServicesInterface) {
            $this->smsProvider = $smsProvider;
        } else {
            $provider = 'ridvanbaluyos\\sms\\providers\\' . $this->randomizeProvider();
            $this->smsProvider = new $provider();
        }
    }

    /**
     * This function sends the SMS.
     *
     * @param $phoneNumber - the mobile number
     * @param $message - the message
     */
    public function send($phoneNumber, $message)
    {
        $phoneNumber = $this->prepareNumber($phoneNumber);
        $this->smsProvider->send($phoneNumber, $message . ' via ' . $this->smsProvider->className);
    }

    /**
     * This function gets the remaining balance of an account
     *
     */
    public function balance()
    {
        $this->smsProvider->balance();
    }

    /**
     * This function randomizes the SMS providers. Make sure that the total
     * of the SMS providers in distribution.json is equal to 1. Otherwise, there's
     * no guarantee that the randomizer will work as expected.
     *
     * @return string $result - the lucky SMS provider
     */
    private function randomizeProvider()
    {
        $providers = Config::load(__DIR__ . '/config/distribution.json');
        $result = 'PromoTexter';
        $rand = (float)rand() / (float)getrandmax();
        foreach ($providers as $provider => $weight) {
            if ($rand < $weight) {
                $result = $provider;
                break;
            }
            $rand -= $weight;
        }

        return $result;
    }

    /**
     * This function generates a random string with a specific length.
     *
     * @param int $length - the length of the message id
     * @return string - returns a random n length string
     */
    protected function generateMessageId($length = 32)
    {
        return str_pad(uniqid(uniqid()), $length, uniqid(), STR_PAD_LEFT);
    }

    /**
     * This function acts a pseudo-envelope for the responses of each SMS providers. It basically
     * standardizes the responses of each.
     *
     * @param $code - the response code
     * @param null $message - an optional message aside from the default ones
     * @param null $provider - the SMS provider used. Should only be enabled during debug mode.
     */
    protected function response($code, $metadata = null, $message = null, $provider)
    {
        switch ($code) {
            // Error Codes
            case 400:
                $envelope = 'error';
                $description = 'Bad Request';
                break;
            case 401:
                $envelope = 'error';
                $description = 'Unauthorized';
                break;
            case 403:
                $envelope = 'error';
                $description = 'Forbidden';
                break;
            case 404:
                $envelope = 'error';
                $description = 'Not Found';
                break;
            case 500:
                $envelope = 'error';
                $description = 'Something went wrong';
                break;
            // Success Codes
            case 200:
                $envelope = 'data';
                $description = 'OK';
                break;
            case 201:
                $envelope = 'data';
                $description = 'Created';
                break;
            case 202:
                $envelope = 'data';
                $description = 'Accepted';
                break;
            default:
                $envelope = 'data';
                $description = '';
                break;
        }

        if (is_null($message)) {
            $message = $description;
        }
        // HTTP Response
        $response = [
            $envelope => [
                'code' => $code,
                'message' => $message,
                'provider' => $provider,
                'metadata' => $metadata
            ],
        ];
        $response = json_encode($response);
        http_response_code($code);
        header("Content-Type: application/json");
        echo $response;
        exit;
    }

    /**
     * This function reformats the mobile number being sent to the Philippine mobile number standard.
     *
     * @param $number - the mobile number
     * @return string - the formatted mobile number
     */
    protected function prepareNumber($number)
    {
        $numbersOnly = preg_replace('/[^0-9]/', '', $number);
        if (strpos($numbersOnly, '09') === 0) {
            $number = '63' . mb_substr($numbersOnly, 1);
        } elseif (strlen($numbersOnly) == 10 && strpos($numbersOnly, '9') === 0) {
            $number = '63' . $numbersOnly;
        }

        return $number;
    }
}

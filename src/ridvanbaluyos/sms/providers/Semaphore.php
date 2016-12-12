<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

/**
 * Class Semaphore
 * @package ridvanbaluyos\sms\providers
 */
class Semaphore extends Sms implements SmsProviderServicesInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * Semaphore constructor.
     */
    public function __construct()
    {
        $this->className = substr(get_called_class(), strrpos(get_called_class(), '\\') + 1);
    }

    /**
     * This function sends the SMS.
     *
     * @param $phoneNumber
     * @param $message
     */
    public function send($phoneNumber, $message)
    {
        try {
            $conf = Config::load(__DIR__ . '/../config/providers.json')[$this->className];
            $query = [
                'from' => $conf['from'],
                'api' => $conf['api'],
                'number' => $phoneNumber,
                'message' => $message,
            ];

            $url = $conf['url'] . '?' . http_build_query($query);

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 240);
            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result);
            $this->response($result->code, $result->message, $this->className);
        } catch (Exception $e) {

        }
    }
}
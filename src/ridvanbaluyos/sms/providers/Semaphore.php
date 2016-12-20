<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;
use Exception as Exception;

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
     * @return string
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

            if ($result->status === 'success') {
                return $this->response(202, $result, null, $this->className);
            } else {
                return $this->response(500, $result->message, null, $this->className);
            }
        } catch (Exception $e) {

        }
    }

    /**
     * This function gets the account balance.
     *
     */
    public function balance()
    {
        try {
            $conf = Config::load(__DIR__ . '/../config/providers.json')[$this->className];
            $query = [
                'api' => $conf['api'],
            ];

            $url = $conf['url'] . '/account?' . http_build_query($query);

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 240);
            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result);

            if ($result->status === 'success') {
                return $this->response(200, $result, null, $this->className);
            } else {
                return $this->response(500, $result->message, null, $this->className);
            }
        } catch (Exception $e) {

        }
    }
}
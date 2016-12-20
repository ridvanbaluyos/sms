<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;
use Exception as Exception;

/**
 * Class Nexmo
 * @package ridvanbaluyos\sms\providers
 */
class Nexmo extends Sms implements SmsProviderServicesInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * Nexmo constructor.
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

            $query = array(
                'api_key' => $conf['api_key'],
                'api_secret' => $conf['api_secret'],
                'from' => $conf['from'],
                'to' => $phoneNumber,
                'text' => $message,
            );

            $query = http_build_query($query);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $conf['url'] . '/sms/json');
            curl_setopt($ch, CURLOPT_POST, count($query));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result);

            if (intval($result->messages[0]->status) === 0) {
                return $this->response(200, $result, null, $this->className);
            } else {
                return $this->response(500, $result, null, $this->className);
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

            $query = array(
                'api_key' => $conf['api_key'],
                'api_secret' => $conf['api_secret']
            );

            $query = http_build_query($query);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $conf['url'] . '/account/get-balance?' . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result);

            if (is_float($result->value) || is_integer($result->value)) {
                return $this->response(200, $result, null, $this->className);
            } else {
                return $this->response(500, $result, null, $this->className);
            }
        } catch (Exception $e) {

        }
    }
}

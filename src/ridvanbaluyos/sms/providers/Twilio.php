<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

/**
 * Class Twilio
 * @package ridvanbaluyos\sms\providers
 */
class Twilio extends Sms implements SmsProviderServicesInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * Twilio constructor.
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
        $phoneNumber = '+' . $phoneNumber;
        try {
            $conf = Config::load(__DIR__ . '/../config/providers.json')[$this->className];

            $query = array(
                'To' => $phoneNumber,
                'From' => $conf['from'],
                'Body' => $message,
            );

            $query = http_build_query($query);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $conf['url']);
            curl_setopt($ch, CURLOPT_POST, count($query));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "{$conf['account_sid']}:{$conf['auth_token']}");
            $result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($result);

            if (is_string($result->status)) {
                $this->response(200, $result, null, $this->className);
            } else {
                $this->response(500, $result, null, $this->className);
            }
        } catch (Exception $e) {

        }
    }
}

<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

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
     */
    public function send($phoneNumber, $message)
    {
        try {
            $conf = Config::load(__DIR__ . '/../config/providers.json')[$this->className];
            $messageId = $this->generateMessageId(32);

            $query = array(
                'api_key' => $conf['api_key'],
                'api_secret' => $conf['api_secret'],
                'from' => $conf['from'],
                'to' => $phoneNumber,
                'text' => $message,
            );

            $query = http_build_query($query);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $conf['url']);
            curl_setopt($ch, CURLOPT_POST, count($query));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result);

            if (intval($result->messages[0]->status) === 0) {
                $this->response(200, $result, null, $this->className);
            } else {
                $this->response(500, $result, null, $this->className);
            }
        } catch (Exception $e) {

        }
    }
}

<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

/**
 * Class Chikka
 * @package ridvanbaluyos\sms\providers
 */
class Chikka extends Sms implements SmsProviderServicesInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * Chikka constructor.
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
                'message_type' => 'SEND',
                'mobile_number' => $phoneNumber,
                'shortcode' => $conf['shortcode'],
                'message_id' => $messageId,
                'message' => $message,
                'client_id' => $conf['client_id'],
                'secret_key' => $conf['secret_key'],
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
            $this->response($result->status, null, $this->className);
        } catch (Exception $e) {

        }
    }
}

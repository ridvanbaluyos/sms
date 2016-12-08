<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

class Chikka extends Sms implements SmsProviderServicesInterface
{
    private $className;

    public function __construct()
    {
        $this->className = substr(get_called_class(), strrpos(get_called_class(), '\\') + 1);
    }

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
            $error = curl_error($ch);
            curl_close($ch);

            $result = json_decode($result);
            $this->response($result->status);
        } catch (Exception $e) {

        }
    }
}

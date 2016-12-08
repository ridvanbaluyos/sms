<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

class PromoTexter extends Sms implements SmsProviderServicesInterface
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
            $query = [
                'senderid' => $conf['senderid'],
                'clientid' => $conf['clientid'],
                'passkey' => $conf['passkey'] . 'dd',
                'msisdn' => $phoneNumber,
                'message' => base64_encode($message),
                'dlr-call' => $conf['dlr-call'],
                'dlr-callback' => '',
            ];

            $url = $conf['url'] . '?' . http_build_query($query);

            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 240);
            $result = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            $code = (intval($result) > 0) ? 202 : 403;
            $this->response($code);
        } catch (Exception $e) {

        }
	}
}

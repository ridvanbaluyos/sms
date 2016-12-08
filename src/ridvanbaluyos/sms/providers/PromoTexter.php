<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

/**
 * Class PromoTexter
 * @package ridvanbaluyos\sms\providers
 */
class PromoTexter extends Sms implements SmsProviderServicesInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * PromoTexter constructor.
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
                'senderid' => $conf['senderid'],
                'clientid' => $conf['clientid'],
                'passkey' => $conf['passkey'] ,
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
            $this->response($code, null, $this->className);
        } catch (Exception $e) {

        }
	}
}

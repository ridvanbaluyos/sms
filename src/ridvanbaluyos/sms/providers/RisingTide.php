<?php
namespace ridvanbaluyos\sms\providers;

use ridvanbaluyos\sms\SmsProviderServicesInterface as SmsProviderServicesInterface;
use ridvanbaluyos\sms\Sms as Sms;
use Noodlehaus\Config as Config;

date_default_timezone_set('Asia/Manila');

/**
 * Class RisingTide
 * @package ridvanbaluyos\sms\providers
 */
class RisingTide extends Sms implements SmsProviderServicesInterface
{
    /**
     * @var string
     */
    protected $className;

    /**
     * RisingTide constructor.
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
            $content = array(
                'id' => $messageId,
                'from' => $conf['from'],
                'to' => $phoneNumber,
                'content_type' => "text/plain",
                'body' => $message,
                'usagetype' => $conf['usagetype'],
                'date' => date('Y-m-d') . 'T' . date('H:i:s') . '+0800',
                'delivery_receipt_url' => '',
            );

            $content = json_encode($content);
            $signature = base64_encode(join(':', array($conf['client_id'], $conf['client_password'])));

            $headers = array(
                'Authorization: Basic ' . $signature,
                'Accept: /documents',
                'Date: ' . date('r'),
                'Content-Type: application/vnd.net.wyrls.Document-v3+json',
                'Content-Length: ' . strlen($content),
            );

            $url = $conf['url'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $this->response($responseCode, null, $this->className);
        } catch (Exception $e) {

        }
    }
}
<?php
namespace ridvanbaluyos\sms;

use ridvanbaluyos\sms\SmsProvider as SmsProvider;

class Sms
{
	private $smsProvider;

	public function __construct(SmsProviderServicesInterface $smsProvider = null)
	{
	    if (is_null($smsProvider)) {
            $this->randomizeProvider();
        }
		$this->smsProvider = $smsProvider;

	}
	
	public function send($phoneNumber, $message)
	{
		$this->smsProvider->send($phoneNumber, $message);
	}

	private function randomizeProvider()
    {

    }

    protected function generateMessageId($length)
    {
        return str_pad(uniqid(uniqid()), $length, uniqid(), STR_PAD_LEFT);
    }

    protected function response($code, $message = null, $data = null)
    {
        switch ($code) {
            // Error Codes
            case 400:
                $envelope = 'error';
                $description = 'Bad Request';
                break;
            case 401:
                $envelope = 'error';
                $description = 'Unauthorize';
                break;
            case 403:
                $envelope = 'error';
                $description = 'Forbidden';
                break;
            case 404:
                $envelope = 'error';
                $description = 'Not Found';
                break;
            case 500;
                $envelope = 'error';
                $description = 'Something went wrong';
                break;
            // Success Codes
            case 200:
                $envelope = 'data';
                $description = 'OK';
                break;
            case 201:
                $envelope = 'data';
                $description = 'Created';
                break;
            case 202:
                $envelope = 'data';
                $description = 'Accepted';
                break;
            default:
                break;
        }

        if (is_null($message)) {
            $message = $description;
        }
        // HTTP Response
        $response = [
            $envelope => [
                'code' => $code,
                'message' => $message
            ],
        ];
        $response = json_encode($response);
        http_response_code($code);
        header("Content-Type: application/json");
        echo $response;
        exit;
    }
}
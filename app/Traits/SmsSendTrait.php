<?php

namespace App\Traits;

use Twilio\Rest\Client;

trait SmsSendTrait
{
    public function twilio($phone_number, $code)
    {
        $account_sid = env('TWILIO_SID');
        $account_token = env('TWILIO_TOKEN');
        $account_from = env('TWILIO_FROM');

        $client = new Client($account_sid, $account_token);
        $client->messages->create(
            $phone_number,
            [
                'from' => $account_from,
                'body' => 'ICarePro Mobile Phone Verification Code ' . $code
            ]
        );
    }

    public function twilioOtpSend($country_code, $phone_number)
    {
        $token = getenv("TWILIO_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($country_code . $phone_number, "sms");
    }

    public function sendPk($phone_number, $code)
    {
        $api_key = env('SENDPK_API_KEY'); ///YOUR API KEY
        $sender = env('SENDPK_FROM');
        $message = "ICarePro Mobile Phone Verification Code " . $code;

        ////sending sms
        $post = "sender=" . urlencode($sender) . "&mobile=" . urlencode($phone_number) . "&message=" . urlencode($message) . "";
        $url = "https://sendpk.com/api/sms.php?api_key=$api_key";
        $ch = curl_init();
        $timeout = 30; // set to zero for no timeout
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($ch);
        /*Print Responce*/
    }
}

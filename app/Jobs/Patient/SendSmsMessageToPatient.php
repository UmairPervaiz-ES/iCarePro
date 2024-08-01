<?php

namespace App\Jobs\Patient;

use App\Helper\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Rest\Client;

class SendSmsMessageToPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

     public $country_code, $phone_number, $practice_name;

    public function __construct($country_code, $phone_number, $practice_name)
    {
        $this->country_code = $country_code;
        $this->phone_number = $phone_number;
        $this->practice_name = $practice_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account_sid = env('TWILIO_SID');
        $account_token = env('TWILIO_TOKEN');
        $account_from = env('TWILIO_FROM');
        $client = new Client($account_sid, $account_token);
        $client->messages->create(
            $this->country_code .   $this->phone_number,
            [
                'from' => $account_from,
                'body' =>   $this->practice_name . ' ' . 'Register you in IcarePro Platform '
            ]
        );
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

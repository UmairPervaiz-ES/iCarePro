<?php

namespace App\Jobs\Patient;

use App\Helper\Helper;
use App\Traits\SmsSendTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsMessageToPatientWithCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use SmsSendTrait;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $country_code, $phone_number, $code;

    public function __construct($country_code, $phone_number, $code)
    {

        $this->country_code = $country_code;
        $this->phone_number = $phone_number;
        $this->code = $code;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $number = $this->country_code . $this->phone_number;
        $phone_number = preg_replace('/[^A-Za-z0-9]/', '', $number);
       
        if($this->country_code == '+92'){
            $this->sendPk($phone_number , $this->code);
        }
        else{
            $this->twilioOtpSend($this->country_code, $this->phone_number);
            // $this->twilio($phone_number , $this->code);
        }

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

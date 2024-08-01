<?php

namespace App\Jobs\Practice;

use App\Helper\Helper;
use App\Mail\Practice\InitialRequestSendAlertToUser;
use App\Mail\Practice\PracticeRegister as PracticePracticeRegister;
use App\Mail\Practice\PracticeRequestSendAlertToUser;
use App\Mail\Practice\SendRegistrationMessageToPatient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendRegistrationLinkToPatient implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $patient, $practice_name;

    public function __construct($patient, $practice_name)
    {
        $this->patient = $patient;
        $this->practice_name = $practice_name;

        $this->onQueue(config('constants.SEND_REGISTRATION_LINK_PATIENT'));


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->patient['email'])->send(new SendRegistrationMessageToPatient($this->patient,$this->practice_name));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

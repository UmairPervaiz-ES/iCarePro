<?php

namespace App\Jobs\Practice;

use App\Helper\Helper;
use App\Mail\Practice\InitialRequestSendAlertToUser;
use App\Mail\Practice\PracticeRegister as PracticePracticeRegister;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class InitialRequestSentAlertToUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $initialPractice;

    public function __construct($initialPractice)
    {
        $this->initialPractice = $initialPractice;
        $this->onQueue(config('constants.INITIAL_REQUEST_AlERT_SEND_TO_USET'));


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->initialPractice['email'])->send(new InitialRequestSendAlertToUser($this->initialPractice));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}
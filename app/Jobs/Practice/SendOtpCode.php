<?php

namespace App\Jobs\Practice;

use App\Helper\Helper;
use App\Mail\Practice\SentOtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class SendOtpCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $details;

    public function __construct($details)
    {
        $this->details = $details;
        $this->onQueue(config('constants.SEND_OTP'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::to($this->details['email'])->send(new SentOtpCode($this->details));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

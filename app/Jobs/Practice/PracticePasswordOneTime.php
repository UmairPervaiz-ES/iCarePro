<?php

namespace App\Jobs\Practice;

use App\Helper\Helper;
use App\Mail\Practice\PracticePasswordOneTime as PracticePracticePasswordOneTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PracticePasswordOneTime implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $practice ,$password;

    public function __construct($practice,$password)
    {
        $this->practice = $practice;
        $this->password = $password;
        $this->onQueue(config('constants.PRACTICE_REQUEST_ACCEPT'));



    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->practice['email'])->send(new PracticePracticePasswordOneTime($this->practice,$this->password));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

<?php

namespace App\Jobs\Practice;

use App\Helper\Helper;
use App\Mail\Practice\PracticeDetailsReject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class PracticeDetailReject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $practice;

    public function __construct($practice)
    {
        $this->practice = $practice;
        $this->onQueue(config('constants.PRACTICE_REQUEST_REJECT'));

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->practice->initialPractice['email'])->send(new PracticeDetailsReject($this->practice));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

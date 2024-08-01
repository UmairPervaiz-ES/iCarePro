<?php

namespace App\Jobs\SuperAdmin;

use App\Helper\Helper;
use App\Mail\SuperAdmin\PracticeRequestReceive as SuperAdminPracticeRequestReceive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class PracticeRequestReceive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $superAdmin ,$initialPractice;

    public function __construct($superAdmin,$initialPractice)
    {
        $this->superAdmin = $superAdmin;
        $this->initialPractice = $initialPractice;
        $this->onQueue(config('constants.SUPER_ADMIN_PRACTICE_REQUEST_RECEIVE'));


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->superAdmin)->send(new SuperAdminPracticeRequestReceive($this->superAdmin,$this->initialPractice));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

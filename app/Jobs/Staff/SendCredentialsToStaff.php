<?php

namespace App\Jobs\Staff;

use App\Helper\Helper;
use App\Mail\Staff\Credentials;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCredentialsToStaff implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    public $user,$password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($password, $user)
    {
        $this->user = $user;
        $this->password = $password;
        $this->onQueue(config('constants.SEND_CREDENTIALS_TO_STAFF'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new Credentials($this->password, $this->user));
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

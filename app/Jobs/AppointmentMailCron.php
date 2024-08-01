<?php

namespace App\Jobs;

use App\Helper\Helper;
use App\Mail\Appointment\Notification;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentMailCron implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

     /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $appointments = DB::SELECT("SELECT * FROM appointments
        Inner Join patients On appointments.patient_id = patients.id WHERE appointments.status = 'Confirm'");
        $dateTime = $appointments[0]->date . ' ' . $appointments[0]->time;
        $date = date('Y-m-d h:i:s', time());
        $d1 = new DateTime($dateTime);
        $d2 = new DateTime($date);
        $interval = $d1->diff($d2);
        $diffInHours   = $interval->h; //8

        foreach ($appointments as $appointment) {
            if ($d2 && $diffInHours >= 2) {
                Mail::to($appointment->email)->send(new Notification());
            }
        }
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

<?php

namespace App\Jobs\Appointment;

use App\Helper\Helper;
use App\Mail\Appointment\DoctorAppointment as AppointmentDoctorAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;


class DoctorAppointment implements ShouldQueue
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
    public $appointment,$doctor, $patient, $practice;

    public function __construct($appointment, $doctor, $patient, $practice)
    {
        $this->appointment = $appointment;
        $this->doctor = $doctor;
        $this->patient = $patient;
        $this->practice = $practice;

        $this->onQueue(config('constants.DOCTOR_APPOINTMENT'));

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->doctor['primary_email'])->send(new AppointmentDoctorAppointment($this->appointment,$this->doctor, $this->patient ,$this->practice));

    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

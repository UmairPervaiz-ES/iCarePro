<?php

namespace App\Console\Commands;

use App\Jobs\AppointmentMailCron as JobsAppointmentMailCron;
use Illuminate\Console\Command;


class AppointmentMailCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        dispatch(new JobsAppointmentMailCron());
    }
}

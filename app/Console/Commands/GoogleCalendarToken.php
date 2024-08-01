<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class GoogleCalendarToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'googleCalenderToken:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

 
    /**
     * Execute the console command.
     *
     * @return  object
     *      */
    public function handle()
    {

      app(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch(new \App\Jobs\GoogleCalendarToken());
         
    }
}

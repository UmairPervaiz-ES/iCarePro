<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SubscriptionJob;
use Illuminate\Foundation\Bus\PendingClosureDispatch;
use Illuminate\Foundation\Bus\PendingDispatch;

class AutoRenewSubscriptionCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return PendingClosureDispatch|PendingDispatch
     */
    public function handle(): PendingDispatch|PendingClosureDispatch
    {
        return dispatch(new SubscriptionJob())->onQueue(config('constants.SUBSCRIPTION_AUTO_RENEW'));
    }
}

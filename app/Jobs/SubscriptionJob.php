<?php

namespace App\Jobs;

use App\Helper\Helper;
use App\libs\Messages\SubscriptionGlobalMessageBook as SGMBook;
use App\Models\Practice\PracticePaymentMethod;
use App\Traits\{RespondsWithHttpStatus, SubscriptionTrait};
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Stripe;

class SubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels , RespondsWithHttpStatus ,SubscriptionTrait;

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
        $this->onQueue(config('constants.SUBSCRIPTION_AUTO_RENEW'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */


    public function handle()
    {
        try{
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $date =  Carbon::yesterday();
            $practices = DB::SELECT("SELECT
            practices.id , practices.subscription_expiry_date, practices.customer_id, practices.subscription_id
            FROM
            practices
            INNER JOIN practice_payment_methods ON practices.id = practice_payment_methods.practice_id
            WHERE DATE(practices.subscription_expiry_date) < '{$date->toDateString()}' AND renew_tries_count < 3 AND practices.auto_renew = true AND
            practice_payment_methods.default_payment_method = true");
            foreach($practices as $practiceData){
                $calculateSubscriptionPrice =  $this->calculateSubscriptionPrice($practiceData->subscription_id);
                $practicePaymentMethod = PracticePaymentMethod::select('card_id')->where([['practice_id' , $practiceData->id] , ['default_payment_method' , TRUE] ])->first();
                $charge = $this->chargeSubscriptionPaymentByCardId( $practicePaymentMethod['card_id'] , $practiceData->customer_id , $calculateSubscriptionPrice['price']);

                if ($charge['status'] == 'succeeded') {

                    $charge_id = $charge['id'];
                    //saving the transaction
                    $request = [
                    'subscription_id' => $practiceData->subscription_id,
                    'practice_id' => $practiceData->id,
                    ];

                    $this->subscriptionTransaction($request, $practicePaymentMethod['card_id'], $practiceData->customer_id, $charge_id, $calculateSubscriptionPrice['price'], @$calculateSubscriptionPrice['subscription_discount_id'],@$calculateSubscriptionPrice['actual_price']);

                    $subscriptionExpiryTime = Carbon::now()->addDays($calculateSubscriptionPrice['duration_days']);

                    DB::Update("UPDATE practices SET
                    subscription_expiry_date = '{$subscriptionExpiryTime}',
                    renew_tries_count = 0
                    WHERE id = '{$practiceData->id}'
                    ");
                    $return =  $this->response( $practiceData , $charge, SGMBook::SUCCESS['BUY_SUBSCRIPTION'], 201, true);
                }
                else{
                    DB::Update("UPDATE practices SET
                    renew_tries_count = renew_tries_count + 1
                    WHERE id = '{$practiceData->id}'
                    ");
                    $return = $this->response( $practiceData , $charge, '-Auto Renew' . ' ' .SGMBook::FAILED['BUY_SUBSCRIPTION_ERR'], 400, false);
                }
                return $return;
            }
        }
        catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

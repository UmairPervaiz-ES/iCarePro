<?php

namespace App\Repositories\Subscription\Eloquent\SubscriptionDiscount;

use App\libs\Messages\SubscriptionGlobalMessageBook as SGMBook;
use App\Models\{Subscription\Subscription, Subscription\SubscriptionDiscount};
use App\Repositories\Subscription\Interfaces\SubscriptionDiscount\SubscriptionDiscountRepositoryInterface;
use App\Traits\{RespondsWithHttpStatus, SubscriptionTrait};
use Illuminate\Http\Request;


class SubscriptionDiscountRepository implements SubscriptionDiscountRepositoryInterface
{
    use RespondsWithHttpStatus, SubscriptionTrait;

    public function __construct(Request $request) {
        $this->request = request()->all();
    }

    /**
     * Description: Create Subscription Discount 
     * 1) If Subscription doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Subscription Discount and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function createSubscriptionDiscount($request)
    {
        $subscription = Subscription::where([['id', $request['subscription_id']], ['status', true]])->first();
        if (!$subscription) {
            $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
        } else {
            if (SubscriptionDiscount::where('subscription_id', $request['subscription_id'])->exists()) {
                $this->changeSubscriptionDiscountStatusEloquent($request['subscription_id'], true);
                $subscription_discount = $this->createSubscriptionDiscountEloquent($request);
            } else {
                $subscription->updated_by = auth()->id();
                $subscription->save();
                $subscription_discount = $this->createSubscriptionDiscountEloquent($request);
            }
            $response = $subscription_discount ; $message = SGMBook::SUCCESS['CREATE_SUBSCRIPTION_DISCOUNT']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }
    
    /**
     * Description: Create Subscription Discount Status
     * 1) If Subscription doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Subscription Discount and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function changeSubscriptionDiscountStatus($request)
    {
        $subscription = Subscription::where([['id', $request['subscription_id']], ['status', true]])->first();
        if (!$subscription) {
            $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $subscription_discount = $this->changeSubscriptionDiscountStatusEloquent($request['subscription_id'], true);
            $response = $subscription_discount ; $message = SGMBook::SUCCESS['CHANGE_SUBSCRIPTION_DISCOUNT_STATUS']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }
}

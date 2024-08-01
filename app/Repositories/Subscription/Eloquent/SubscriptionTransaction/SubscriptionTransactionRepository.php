<?php

namespace App\Repositories\Subscription\Eloquent\SubscriptionTransaction;

use App\libs\Messages\SubscriptionGlobalMessageBook as SGMBook;
use App\Models\{Practice\Practice, Practice\PracticePaymentMethod, Subscription\SubscriptionTransaction};
use App\Repositories\Subscription\Interfaces\SubscriptionTransaction\SubscriptionTransactionRepositoryInterface;
use App\Traits\{RespondsWithHttpStatus, SubscriptionTrait};
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;

class SubscriptionTransactionRepository implements SubscriptionTransactionRepositoryInterface
{
    use RespondsWithHttpStatus;
    use SubscriptionTrait;

    public function __construct(Request $request) {
        $this->request = request()->all();
    }

    /**
     * Description: Add New Payment Method On Stripe Against Customer
     * 1) If Practice doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Practice Payment Method and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function addNewPaymentMethodOnStripeAgainstCustomer($request)
    {
        $practice = Practice::find(auth()->id());
        if (!$practice) {
            $response = false; $message = SGMBook::FAILED['FIND_PRACTICE_ERR']; $status = 400; $success = false;
        } else {
            $this->updatePaymentMethodEloquent(auth()->id());
            $card = $this->addNewCardOnStripe($request['card_no'],  $request['ccExpiryMonth'], $request['ccExpiryYear'], $request['cvvNumber'],  $practice->customer_id);
            $practicePaymentMethod = $this->practicePaymentMethodEloquent($card , $request['card_holder_name']);
            $response = $practicePaymentMethod ; $message = SGMBook::SUCCESS['ADD_PAYMENT_METHOD']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Buy Subscription
     * 1) If Subscription doesn't exist. It will return error
     * 2) If Practice doesn't exist. It will return error
     * 3) Activity is logged
     * 4) Practice and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function buySubscription($request)
    {
        $calculateSubscriptionPrice =  $this->CalculateSubscriptionPrice($request['subscription_id']);
        if (!$calculateSubscriptionPrice) {
            $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $practice = Practice::find(auth()->id());
            //create new customer
            if (empty($practice->customer_id)) {
                $practice->customer_id = $this->addNewCustomerOnStripe($practice->email);
                $card = $this->addNewCardOnStripe($request['card_no'],  $request['ccExpiryMonth'], $request['ccExpiryYear'], $request['cvvNumber'],  $practice->customer_id);
                // saving card info in tbl
                $this->practicePaymentMethodEloquent($card, $request['card_holder_name']);
            }
            $practicePaymentMethod = PracticePaymentMethod::select('card_id')->where([['practice_id', auth()->id()], ['default_payment_method', TRUE]])->orderBy('created_at' , "desc")->first();
            $charge = $this->chargeSubscriptionPaymentByCardId($practicePaymentMethod['card_id'], $practice->customer_id, $calculateSubscriptionPrice['price']);
            if ($charge['status'] == 'succeeded') {
                $charge_id = $charge['id'];
                //saving the transaction
                $this->subscriptionTransaction($request, $practicePaymentMethod['card_id'], $practice->customer_id, $charge_id, $calculateSubscriptionPrice['price'], @$calculateSubscriptionPrice['subscription_discount_id'], @$calculateSubscriptionPrice['actual_price']);
                // get all permissions assigned to the subscription
                $this->assignSubscriptionPermissions($practice, $request['subscription_id']);
                $subscriptionExpiryTime = Carbon::now()->addDays($calculateSubscriptionPrice['duration_days']);
                $practice->subscription_expiry_date = $subscriptionExpiryTime;
                $practice->save();
                $response = $practice ; $message = SGMBook::SUCCESS['BUY_SUBSCRIPTION']; $status = 201; $success = true;
            } else {
                $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
            }
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Change Subscription Plan
     * 1) If Subscription doesn't exist. It will return error
     * 2) If Practice doesn't exist. It will return error
     * 3) Activity is logged
     * 4) Practice and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function changeSubscription($request)
    {
        $calculateSubscriptionPrice =  $this->CalculateSubscriptionPrice($request['subscription_id']);
        if (!$calculateSubscriptionPrice) {
            $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
        } else{
            $practice = Practice::find(auth()->id());
            $practicePaymentMethod = PracticePaymentMethod::select('card_id')->where([['practice_id', auth()->id()], ['default_payment_method', TRUE]])->first();
            $charge = $this->chargeSubscriptionPaymentByCardId($practicePaymentMethod['card_id'], $practice->customer_id, $calculateSubscriptionPrice['price']);
            if ($charge['status'] == 'succeeded') {
                $charge_id = $charge['id'];
                //saving the transaction
                $this->subscriptionTransaction($request, $practicePaymentMethod['card_id'], $practice->customer_id, $charge_id, $calculateSubscriptionPrice['price'], @$calculateSubscriptionPrice['subscription_discount_id'], @$calculateSubscriptionPrice['actual_price']);
                // get all permissions assigned to the subscription
                $this->assignSubscriptionPermissions($practice, $request['subscription_id']);
                $subscriptionExpiryTime = Carbon::now()->addDays($calculateSubscriptionPrice['duration_days']);
                $practice->subscription_expiry_date = $subscriptionExpiryTime;
                $practice->save();
                $response = $practice ; $message = SGMBook::SUCCESS['BUY_SUBSCRIPTION']; $status = 201; $success = true;
            } else {
                $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
            }
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Change Practice Default Payment Method
     * 1) Activity is logged
     * 2) Practice Payment Method and success message is return
     * 
     * @param  mixed $request
     * @return void
     */
    public function changeDefaultPaymentMethod($request)
    {
        $practicePaymentMethod = PracticePaymentMethod::where([['practice_id', auth()->id()], ['card_id', $request['card_id']]])->first();
        if (!$practicePaymentMethod) {
            $response = false; $message = SGMBook::FAILED['CHANGE_DEFAULT_PAYMENT']; $status = 400; $success = false;
        } else {
            $this->updatePaymentMethodEloquent(auth()->id());
            $practicePaymentMethod->default_payment_method = True;
            $practicePaymentMethod->save();
            $response = $practicePaymentMethod ; $message = SGMBook::SUCCESS['CHANGE_DEFAULT_PAYMENT']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: List Practices Payment Methods
     * 1) Payment Methods return
     *
     * @return void
     */
    public function listPaymentMethods()
    {
        $response  = PracticePaymentMethod::paginate(10);
        $request = null; $status = 200; $success = true; $message = SGMBook::SUCCESS['GET_PAYMENT_METHODS_LIST'];
        return $this->response($request, $response, $message , $status , $success);
    }

    /**
     * Description: Show Transactions
     * 1) Transactions with pagination return
     *
     * @return void
     */
    public function listTransactions()
    {
        $response  = SubscriptionTransaction::paginate(10);
        $request = null; $status = 200; $success = true; $message = SGMBook::SUCCESS['GET_TRANSACTIONS_LIST'];
        return $this->response($request, $response, $message , $status , $success);
    }

     /**
     * Description: Show Transactions against subscription id
     * 1) Transactions return
     *
     * @param  mixed $id
     * @return void
     */
    public function showTransaction($id)
    {
        $request = ['id' => $id];
        Validator::make($request, ['id' => 'numeric|min:1']);
        $response  = SubscriptionTransaction::where('subscription_id', $id)->paginate(15);
        $request = null; $status = 200; $success = true; $message = SGMBook::SUCCESS['GET_TRANSACTION'];
        return $this->response($request, $response, $message , $status , $success);
    }
}

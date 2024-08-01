<?php
namespace App\Traits;

use App\libs\Messages\SubscriptionGlobalMessageBook as SGMBook;
use App\Models\{Practice\PracticePaymentMethod,
    Subscription\Subscription,
    Subscription\SubscriptionDiscount,
    Subscription\SubscriptionPermission,
    Subscription\SubscriptionTransaction};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Stripe;

trait SubscriptionTrait
{
    use RespondsWithHttpStatus;

    /**
     * Description: Create Subscription Discount Eloquent Function
     * 1) Subscription discount will be created
     * 2) Subscription discount return
     * 
     * @param  mixed $request
     * @return void
     */
    protected function createSubscriptionDiscountEloquent($request){
        $subscription_discount = new SubscriptionDiscount;
        $subscription_discount->plan_discount_percent = $request['plan_discount_percent'];
        $subscription_discount->subscription_id = $request['subscription_id'];
        $subscription_discount->plan_discount_till = $request['plan_discount_till'];
        $subscription_discount->status = True;
        $subscription_discount->created_by = auth()->guard('superAdmin-api')->user()->name;
        $subscription_discount->save();
        return $subscription_discount;
    }

    /**
     * Description: Change Subscription Discount Status Eloquent
     * 1) Subscription discount Status will be changed
     * 2) Subscription discount return
     * 
     * @param  mixed $request
     * @param  mixed $status
     * @return void
     */
    protected function changeSubscriptionDiscountStatusEloquent($request , $status){
        return SubscriptionDiscount::where('subscription_id', $request)->update(['status' => !$status]);
    }
    
    /**
     * Description: Calculate Subscription Price with discount percentage
     * 1) Subscription Price will be Calculated
     * 2) price, subscription_discount_id, duration_days  return
     * 
     * @param  mixed $subscription_id
     * @return void
     */
    protected function calculateSubscriptionPrice($subscription_id)
    {
        //get the subscription with the active discount data
        $subscription_collection = Subscription::with(['subscriptionDiscount' => function ($query) {$query->where('status', true);}
        ])->where([['id', $subscription_id],['status' , true]])->first();
        // assign the data to the subscription info vars
        if($subscription_collection){
            $actual_price = $subscription_collection->price;
            $subscription_price = $subscription_collection->price;
            $subscription_duration = $subscription_collection->duration_days;
            // assign the data to the discount subscription info vars
            if($subscription_collection->subscriptionDiscount){
                foreach($subscription_collection->subscriptionDiscount as $discount){
                    $discount_percent = $discount->plan_discount_percent;
                    $valid_till = $discount->plan_discount_till;
                    $discount_id = $discount->id;
                }
                // checking the validity of discount
                $todayDate = Carbon::now(); $expDate = Carbon::parse($valid_till);
                if( $todayDate <= $expDate){ $discountPrice =  ($discount_percent/100)*$subscription_price; $price = $subscription_price-$discountPrice;}
                else { $price =  $subscription_price; }
                $data = ['actual_price' => $actual_price,'price' => (int)$price, 'subscription_discount_id' => (int)$discount_id, 'duration_days' =>  (int)$subscription_duration,];
            }
            else{
                $price =  $subscription_price;
                $data = ['actual_price' => $actual_price, 'price' => (int)$price,'duration_days' =>  $subscription_duration];
            }
            return $data;
        }
    }

     /**
     * Description: Add New Customer On Stripe
     * 
     * @param  mixed $email
     * @return void
     */
    protected function addNewCustomerOnStripe($email){
        $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = $stripe->customers()->create([
            'email' => $email,
        ]);
        return $customer['id'];
    }

    /**
     * Description: Add New Card On Stripe
     *
     * @param  mixed $card_no
     * @param  mixed $ccExpiryMonth
     * @param  mixed $ccExpiryYear
     * @param  mixed $cvvNumber
     * @param  mixed $p_customer_id
     * @return void
     */
    protected function addNewCardOnStripe($card_no,  $ccExpiryMonth , $ccExpiryYear , $cvvNumber ,  $p_customer_id){
        $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));

        //create new card against customer
        $token = $stripe->tokens()->create([
            'card' => [
                'number' => $card_no,
                'exp_month' => $ccExpiryMonth,
                'exp_year' => $ccExpiryYear,
                'cvc' => $cvvNumber,
            ],
        ]);
        $card = $stripe->cards()->create($p_customer_id , $token['id']);

        if (!isset($token['id'])) {
            return $this->response( request()->all(), $token, SGMBook::FAILED['BUY_SUBSCRIPTION_ERR'], 400, false);
        }
        return $card;
    }

    /**
     * Description: Update Payment Method Eloquent
     *
     * @param  mixed $request
     * @return void
     */
    protected function updatePaymentMethodEloquent($request){
        $auth = auth()->guard('practice-api')->user()->practice_key;
        return DB::UPDATE("Update
        practice_payment_methods
        SET default_payment_method = False,
        updated_by = '{$auth}'
        Where practice_id = '{$request}' AND default_payment_method = True");
    }

    /**
     * Description: Practice Payment Method Eloquent
     *
     * @param  mixed $card
     * @param  mixed $card_name
     * @return void
     */
    protected function practicePaymentMethodEloquent($card, $card_name){
        $auth =  auth()->id();
        $auth_key = auth()->guard('practice-api')->user()->practice_key;

        DB::UPDATE("Update
        practice_payment_methods
        SET default_payment_method = False,
        updated_by = '{$auth_key}'
        Where practice_id = '{$auth}' AND default_payment_method = True");

        return PracticePaymentMethod::create([
            'card_holder_name' => $card_name,
            'practice_id' => $auth,
            'card_id' => $card['id'],
            'customer_id' => $card['customer'],
            'brand' => $card['brand'],
            'country' => $card['country'],
            'cvc_check' => $card['cvc_check'],
            'dynamic_last4' => $card['dynamic_last4'],
            'exp_month' => $card['exp_month'],
            'exp_year' => $card['exp_year'],
            'fingerprint' => $card['fingerprint'],
            'funding' => $card['funding'],
            'last4' => $card['last4'],
            'address_city' => $card['address_city'],
            'address_country' => $card['address_country'],
            'address_line1' => $card['address_line1'],
            'address_line1_check' => $card['address_line1_check'],
            'address_line2' => $card['address_line2'],
            'address_state' => $card['address_state'],
            'address_zip' => $card['address_zip'],
            'address_zip_check' => $card['address_zip_check'],
            'tokenization_method' => $card['tokenization_method'],
            'default_payment_method' => True,
            'created_by' => $auth_key
        ]);
    }

    /**
     * Description: Charge Subscription Payment By Card Id
     *
     * @param  mixed $card_id
     * @param  mixed $cust_id
     * @param  mixed $price
     * @return void
     */
    protected function chargeSubscriptionPaymentByCardId($card_id , $cust_id , $price){
        $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));

        return $stripe->charges()->create([
            'card' => $card_id,
            'customer' =>  $cust_id,
            'currency' => 'USD',
            'amount' => $price,
            'description' => 'wallet',
        ]);
    }

    /**
     * Description: subscription Transaction
     *
     * @param  mixed $request
     * @param  mixed $card_id
     * @param  mixed $cust_id
     * @param  mixed $charge_id
     * @param  mixed $price
     * @param  mixed $subscription_discount_id
     * @return void
     */
    protected function subscriptionTransaction($request, $card_id, $cust_id, $charge_id, $price, $subscription_discount_id , $actual_price){
        $subscriptionTransaction = new SubscriptionTransaction;
        $subscriptionTransaction->subscription_id =  $request['subscription_id'];
        $subscriptionTransaction->practice_id =  auth()->id();
        $subscriptionTransaction->card_id = $card_id;
        $subscriptionTransaction->customer_id =  $cust_id;
        if(!empty($subscription_discount_id)){
            $subscriptionTransaction->subscription_discount_id = $subscription_discount_id;
        }
        $subscriptionTransaction->charge_id =  $charge_id;
        $subscriptionTransaction->amount_paid = $price;
        $subscriptionTransaction->actual_amount = $actual_price;
        $subscriptionTransaction->status = True;
        $subscriptionTransaction->created_by = auth()->guard('practice-api')->user()->practice_key;
        $subscriptionTransaction->save();
        return $subscriptionTransaction;
    }

    /**
     * Description: Assign Subscription Permissions/role to practice
     *
     * @param  mixed $practice
     * @param  mixed $subscription_id
     * @return void
     */
    public function assignSubscriptionPermissions($practice , $subscription_id){
        $permissions = SubscriptionPermission::where('subscription_id', $subscription_id)->pluck('permission_id')->toArray();
        if(! $practice->hasRole('practice-'.$practice->id.'@Admin')){
            $role = Role::create(['guard_name' => 'practice-api', 'name' => 'practice-'.$practice->id.'@Admin']);
            $practice->assignRole($role->name);
            foreach($permissions as $permission)
            {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission,
                ]);
            }
        }
        else{
            $role =  Role::where('name', 'practice-'.$practice->id.'@Admin')->first();
            DB::delete("Delete From role_has_permissions where role_id = '{$role->id}';");
            foreach($permissions as $permission)
            {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission,
                ]);
            }
        }
        return true;
    }
    //subscription transaction


}

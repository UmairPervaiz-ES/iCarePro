<?php

namespace App\Repositories\Subscription\Interfaces\SubscriptionTransaction;

interface SubscriptionTransactionRepositoryInterface 
{
    public function buySubscription($request);
    
    public function listTransactions();

    public function showTransaction($id);

    public function addNewPaymentMethodOnStripeAgainstCustomer($request);
    
    public function changeSubscription($request);

    public function changeDefaultPaymentMethod($request);

    public function listPaymentMethods();
}
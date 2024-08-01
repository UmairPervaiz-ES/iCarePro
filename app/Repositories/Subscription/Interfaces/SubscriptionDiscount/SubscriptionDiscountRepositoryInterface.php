<?php

namespace App\Repositories\Subscription\Interfaces\SubscriptionDiscount;

interface SubscriptionDiscountRepositoryInterface 
{
    public function createSubscriptionDiscount($request);

    public function changeSubscriptionDiscountStatus($request);
}
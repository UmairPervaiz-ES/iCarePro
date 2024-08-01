<?php

namespace App\Repositories\Subscription\Interfaces\Subscription;

interface SubscriptionRepositoryInterface 
{
    public function createSubscription($request);

    public function getSubscriptions($Limit_Per_Page);

    public function changeSubscriptionStatus($request);

    public function showAllPermissions();

    public function viewSubscription($id);
    
    public function editSubscription($request);
}
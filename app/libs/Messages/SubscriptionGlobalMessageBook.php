<?php

namespace App\libs\Messages;

class SubscriptionGlobalMessageBook
{
    const FAILED = [
      //  'TEST' => 'Sorry! Your account is not active, kindly verify your account and try to login again.',
        'BUY_SUBSCRIPTION_ERR'=>'Subscription purchase error.',
        'SUBSCRIPTION_NOT_FOUND' => 'No subscription found.',
        'FIND_PRACTICE_ERR' => 'No practice found.',
        'CHANGE_DEFAULT_PAYMENT' => 'No payment method found.',
        'PERMISSIONS' => 'No permissions found.',

    ];

    const SUCCESS = [
     'SUBSCRIPTION_CREATED'=>'Subscription created.',
     'CHANGE_SUBSCRIPTION_STATUS' => 'Subscription status changed.',
     'SUBSCRIPTION_EDIT'=>'Subscription updated successfully.',
     'CREATE_SUBSCRIPTION_DISCOUNT'=>'Subscription discount created.',
     'CHANGE_SUBSCRIPTION_DISCOUNT_STATUS'=>'Subscription discount disabled.',
     'BUY_SUBSCRIPTION'=>'Subscription purchased successfully.',
     'ADD_PAYMENT_METHOD' => 'Payment method added.',
     'CHANGE_DEFAULT_PAYMENT' => 'Default payment method changed.',
     'GET_PERMISSIONS' => 'Permissions list received.',
     'VIEW_SUBSCRIPTION' => 'Subscriptions list received.',
     'GET_SUBSCRIPTION_LIST' => 'Subscriptions list received.',
     'GET_PAYMENT_METHODS_LIST' => 'Payment methods list received.',
     'GET_TRANSACTIONS_LIST' => 'Transaction list received.',
     'GET_TRANSACTION' => 'Transaction details received.'
    ];
}

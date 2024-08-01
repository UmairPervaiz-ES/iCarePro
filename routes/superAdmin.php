<?php

use App\Http\Controllers\SuperAdmin\Auth\AuthController;
use App\Http\Controllers\Subscription\Subscription\SubscriptionController;
use App\Http\Controllers\Subscription\SubscriptionDiscount\SubscriptionDiscountController;
use App\Http\Controllers\Subscription\SubscriptionTransaction\SubscriptionTransactionController;
use App\Http\Controllers\SuperAdmin\Dashboard\DashboardController;
use App\Http\Controllers\SuperAdmin\Practice\PracticeController;
use App\Http\Controllers\SuperAdmin\PracticeRequest\PracticeRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'superAdmin'], function () {
    //admin route
    Route::post('login', [AuthController::class, 'login']);

    //apply middleware
    Route::middleware(['auth:superAdmin-api'])->group(function () {
        // Update Profile Settings
        Route::get('dashboard', [DashboardController::class, 'dashboard']);

        // Update Profile Settings
        Route::post('initial-practice-request', [PracticeRequestController::class, 'initialPracticeRequest']);
        Route::post('initial-practice-response', [PracticeRequestController::class, 'initialPracticeRequestResponse']);
        Route::post('practice-request', [PracticeRequestController::class, 'practiceRequestGet']);
        Route::post('practice-request-response', [PracticeRequestController::class, 'practiceRequestResponse']);
        Route::post('practices', [PracticeController::class, 'practices']);
        Route::get('practice-details/{id}', [PracticeController::class, 'practiceDetails']);

        // route for creating subscription
        Route::post('create-subscription', [SubscriptionController::class, 'createSubscription']);
        // route for fetching subscription All
        Route::get('subscriptions/{Limit_Per_Page}', [SubscriptionController::class, 'getSubscriptions']);
        // route for Change subscription Status by ID
        Route::post('change-subscription-status/', [SubscriptionController::class, 'changeSubscriptionStatus']);
        // route for creating Discount
        Route::post('create-subscription-discount', [SubscriptionDiscountController::class, 'createSubscriptionDiscount']);
        // route for Show ALL Permissions
        Route::get('show-all-permissions', [SubscriptionController::class, 'showAllPermissions']);
        // route for Show a Subscription {id}
        Route::get('view-subscription/{id}', [SubscriptionController::class, 'viewSubscription']);
        // route for List Transaction
        Route::get('list-transactions', [SubscriptionTransactionController::class, 'listTransactions']);
        // route for Show Transaction by Subscription Id
        Route::get('show-transaction/{id}', [SubscriptionTransactionController::class, 'showTransaction']);
        // route for Edit Subscription
        Route::post('edit-subscription', [SubscriptionController::class, 'editSubscription']);
        // route for ChangeSubscriptionDiscountStatus
        Route::post('change-subscription-discount-status', [SubscriptionDiscountController::class, 'changeSubscriptionDiscountStatus']);
        // List all Payment Methods
        Route::get('list-payment-methods', [SubscriptionTransactionController::class, 'listPaymentMethods']);
    });
});



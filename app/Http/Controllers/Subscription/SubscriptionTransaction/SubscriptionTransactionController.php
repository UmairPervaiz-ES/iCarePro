<?php

namespace App\Http\Controllers\Subscription\SubscriptionTransaction;

use App\Http\Controllers\Controller;
use App\Repositories\Subscription\Interfaces\SubscriptionTransaction\SubscriptionTransactionRepositoryInterface;
use App\Http\Requests\Subscription\SubscriptionTransaction\{BuySubscriptionRequest,AddNewPaymentMethodRequest,ChangeSubscriptionRequest,ChangeDefaultPaymentMethodRequest};

class SubscriptionTransactionController extends Controller
{

    private SubscriptionTransactionRepositoryInterface $subscriptionTransactionRepository;
    public function __construct(SubscriptionTransactionRepositoryInterface $subscriptionTransactionRepository)
    {
        $this->subscriptionTransactionRepository = $subscriptionTransactionRepository;
    }


    /**
     * @OA\Post(
     *      path="/backend/api/practice/buy-subscription/",
     *      operationId="buySubscription",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="buy subscription",
     *      description="buy subscription",
     *      @OA\Parameter(
     *      name="subscription_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="card_no",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="ccExpiryMonth",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="ccExpiryYear",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="cvvNumber",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="card_holder_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function buySubscription(BuySubscriptionRequest $request)
    {
        return $this->subscriptionTransactionRepository->buySubscription($request->all());
    }

    /**
     * @OA\Get(
     *      path="/backend/api/superAdmin/list-transactions/",
     *      operationId="listTransactions",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="list transactions",
     *      description="list transactions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function listTransactions(){
        return $this->subscriptionTransactionRepository->listTransactions();
    }

      /**
     * @OA\Get(
     *      path="/backend/api/superAdmin/show-transaction/{id}",
     *      operationId="showTransaction",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="show transactions by subscription_id",
     *      description="show transactions by subscription_id",
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function showTransaction($id){
        return $this->subscriptionTransactionRepository->showTransaction($id);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/add-new-payment-method-on-stripe-against-customer/",
     *      operationId="addNewPaymentMethodOnStripeAgainstCustomer",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="add new payment method on stripe against customer",
     *      description="add new payment method on stripe against customer",
     *      @OA\Parameter(
     *      name="card_no",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="ccExpiryMonth",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="ccExpiryYear",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="cvvNumber",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Parameter(
     *      name="card_holder_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function addNewPaymentMethodOnStripeAgainstCustomer(AddNewPaymentMethodRequest $request){
        return $this->subscriptionTransactionRepository->addNewPaymentMethodOnStripeAgainstCustomer($request);
    }

    /**
     * @OA\Post(
     *      path="/backend/api/practice/change-subscription/",
     *      operationId="changeSubscription",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="change subscription",
     *      description="change subscription",
     *      @OA\Parameter(
     *      name="subscription_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function changeSubscription(ChangeSubscriptionRequest $request){
        return $this->subscriptionTransactionRepository->changeSubscription($request);
    }

        /**
     * @OA\Post(
     *      path="/backend/api/practice/change-default-payment-method/",
     *      operationId="changeDefaultPaymentMethod",
     *      tags={"Practice"},
     *      security={{"passport":{}}},
     *      summary="change default payment method",
     *      description="change default payment method",
     *      @OA\Parameter(
     *      name="card_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function changeDefaultPaymentMethod(ChangeDefaultPaymentMethodRequest $request){
        return $this->subscriptionTransactionRepository->changeDefaultPaymentMethod($request);
    }

    /**
     * @OA\Get(
     *      path="/backend/api/superAdmin/list-payment-methods/",
     *      operationId="listPaymentMethods",
     *      tags={"SuperAdmin"},
     *      security={{"passport":{}}},
     *      summary="list practice payment methods",
     *      description="list practice payment methods",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *  )
     */
    public function listPaymentMethods(){
        return $this->subscriptionTransactionRepository->listPaymentMethods();
    }

}

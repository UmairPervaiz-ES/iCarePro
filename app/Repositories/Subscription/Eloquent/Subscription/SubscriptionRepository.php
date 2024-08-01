<?php

namespace App\Repositories\Subscription\Eloquent\Subscription;

use App\libs\Messages\SubscriptionGlobalMessageBook as SGMBook;
use App\Models\{Subscription\Subscription, Subscription\SubscriptionPermission};
use App\Repositories\Subscription\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Traits\{RespondsWithHttpStatus, SubscriptionTrait};
use Spatie\Permission\Models\Permission;
use Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    use RespondsWithHttpStatus, SubscriptionTrait;

    public function __construct(Request $request) {
        $this->request = request()->all();
    }

    /**
     * Description: Create Subscription
     * 1) Activity is logged
     * 2) Subscription and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function createSubscription($request)
    {
        // make query with eloquent to create new subscription
        $subscription  = new Subscription;
        $subscription->name = $request['name'];
        $subscription->description = $request['description'];
        $subscription->duration_days = $request['duration_days'];
        $subscription->price = $request['price'];
        $subscription->allowed_doctors = $request['allowed_doctors'];
        $subscription->allowed_staff = $request['allowed_staff'];
        $subscription->allowed_patients = $request['allowed_patients'];
        $subscription->allowed_appointments = $request['allowed_appointments'];
        $subscription->is_trial = $request['is_trial'];
        $subscription->status = $request['status'];
        // $subscription->is_discount_available = False;
        $subscription->created_by = auth()->guard('superAdmin-api')->user()->name;
        $subscription->save();
        $subscription_id = $subscription->id;

        // assigning permissions to the role that is created above
        foreach ($request['permissions'] as $permission) {
            $subscriptionPermission = new SubscriptionPermission;
            $subscriptionPermission->subscription_id = $subscription_id;
            $subscriptionPermission->permission_id = $permission;
            $subscriptionPermission->save();
        }
        return $this->response(request()->all(), $subscription, SGMBook::SUCCESS['SUBSCRIPTION_CREATED'], 201);
    }

     /**
     * Description: Get subscriptions
     * 1) Subscription with pagination return
     *
     * @param  mixed $limit_per_page
     * @return void
     */
    public function getSubscriptions($limit_per_page)
    {
        $request = ['Limit_Per_Page' => $limit_per_page];
        Validator::make($request, ['Limit_Per_Page' => 'numeric|min:1']);
        $response  = Subscription::paginate($limit_per_page);
        $request = null; $status = 200; $success = true; $message = SGMBook::SUCCESS['GET_SUBSCRIPTION_LIST'];
        return $this->response($request, $response, $message , $status , $success);
    }

    /**
     * Description: Change Subscription Status 
     * 1) If Subscription exist. Then Its status will be updated
     * 2) Activity is logged
     * 3) Subscription and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function changeSubscriptionStatus($request)
    {
        $subscription  =  Subscription::find($request['subscription_id']);
        if (!$subscription) {
            $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $subscription->status = $request['status'];
            $subscription->updated_by = auth()->guard('superAdmin-api')->user()->name;
            $subscription->save();
            $response = $subscription; $message = SGMBook::SUCCESS['CHANGE_SUBSCRIPTION_STATUS']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }

    /**
     * Description: Show all permissions
     * 1) Permissions return
     *
     * @return void
     */
    public function showAllPermissions()
    {
        $role = Role::with(['permissions' => function($query){
            return $query->where('guard_name', 'api');
        }])->where('name', '=',auth()->guard('practice-api')->user()->getRoleNames())->first();

        if (!$role)
        {
            $request = null; $response = null; $status = 400; $success = false; $message = SGMBook::FAILED['PERMISSIONS'];
        }
        else{
            $response  = $role->permissions->groupBy(function ($val){return substr($val->name, 0, strpos($val->name, "-")); });
            $request = null; $status = 200; $success = true; $message = SGMBook::SUCCESS['GET_PERMISSIONS'];
        }
        return $this->response($request, $response, $message , $status ,  $success);
    }

    /**
     * Description: View subscription
     * 1) Subscription return
     *
     * @param  mixed $id
     * @return void
     */
    public function viewSubscription($id)
    {
        $request = ['id' => $id];
        Validator::make($request, ['id' => 'numeric|min:1']);
        $response  =  Subscription::where('id', $request['id'])->with('SubscriptionDiscount')->get();
        $request = null; $status = 200; $success = true; $message = SGMBook::SUCCESS['VIEW_SUBSCRIPTION'];
        return $this->response($request, $response, $message , $status , $success);

    }

    /**
     * Description: Edit Subscription
     * 1) If Subscription doesn't exist. It will return error
     * 2) Activity is logged
     * 3) Subscription and success message is return
     *
     * @param  mixed $request
     * @return void
     */
    public function editSubscription($request)
    {
        // make query with eloquent to create new subscription
        $subscription  = Subscription::find($request['id']);
        if (!$subscription) {
            $response = false; $message = SGMBook::FAILED['SUBSCRIPTION_NOT_FOUND']; $status = 400; $success = false;
        } else {
            $subscription->name = $request['name'];
            $subscription->description = $request['description'];
            $subscription->price = $request['price'];
            $subscription->status = $request['status'];
            $subscription->updated_by = auth()->guard('superAdmin-api')->user()->name;
            $subscription->save();
            $response = $subscription; $message = SGMBook::SUCCESS['SUBSCRIPTION_EDIT']; $status = 201; $success = true;
        }
        return $this->response($this->request, $response, $message ,$status, $success);
    }
}

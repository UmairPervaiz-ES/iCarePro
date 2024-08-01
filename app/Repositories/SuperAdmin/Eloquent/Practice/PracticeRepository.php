<?php

namespace App\Repositories\SuperAdmin\Eloquent\Practice;

use App\Jobs\Practice\PracticeDetailReject;
use App\Jobs\Practice\PracticePasswordOneTime;
use App\Jobs\Practice\PracticeRegister as PracticePracticeRegister;
use App\Jobs\Practice\PracticeRegisterReject as PracticePracticeRegisterReject;
use App\libs\Messages\SuperAdminGlobalMessageBook;
use App\Models\Practice\InitialPractice;
use App\Models\Practice\Practice;
use App\Models\Subscription\SubscriptionPermission;
use App\Repositories\SuperAdmin\Interfaces\Practice\PracticeRepositoryInterface;
use App\Repositories\SuperAdmin\Interfaces\PracticeRequest\PracticeRequestRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
class PracticeRepository implements PracticeRepositoryInterface
{
    use RespondsWithHttpStatus;

    /**
     *  Description: Function for retrieving all registered practices
     *  1) This method is used to list registered practices
     *  2) Activity is logged, and a success message is return
     * @param $request
     * @return Response
     */
    public function practices($request): Response
    {
        $practices = Practice::with('initialPractice:id,practice_name', 'subscription:id,name')
            ->select(['id', 'practice_registration_request_id', 'subscription_id','created_at', 'status'])
            ->withCount('practicePatient', 'doctors', 'staffs')
            ->where('status', 'Accepted')
            ->paginate($request['records']);
        return $this->response(true, $practices, SuperAdminGlobalMessageBook::SUCCESS['PRACTICE_LIST'], 200);
    }

    /**
     *  Description: Function for retrieving practice details
     *  1) This method is used to retrieve practice details
     *  2) Activity is logged, and a success message is return
     * @param $id
     * @return Response
     */
    public function practiceDetails($id): Response
    {
        $practices = Practice::with('initialPractice', 'practiceAddress', 'practiceBillingAddress',
            'subscription:id,name', 'alternativeContacts')
            ->where('id', $id)
            ->first();
        return $this->response(true, $practices, SuperAdminGlobalMessageBook::SUCCESS['PRACTICE_DETAILS'], 200);
    }
}

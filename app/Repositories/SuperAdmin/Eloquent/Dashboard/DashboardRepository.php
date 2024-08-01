<?php

namespace App\Repositories\SuperAdmin\Eloquent\Dashboard;

use App\libs\Messages\SuperAdminGlobalMessageBook;
use App\Models\Doctor\Doctor;
use App\Models\Practice\InitialPractice;
use App\Models\Practice\Practice;
use App\Repositories\SuperAdmin\Interfaces\Dashboard\DashboardInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Http\Response;

class DashboardRepository implements DashboardInterface
{
    use RespondsWithHttpStatus;


    /**
     *  Description: This function used to display stats for superAdmin dashboard
     *  1) Activity is logged, and a success message is return
     * @return Response
     */
    public function dashboard(): Response
    {
        $stats['total_doctors'] = Doctor::get()->count();
        $stats['total_practices_registered'] = Practice::get()->count();
        $stats['total_active_practices'] = Practice::where('subscription_expiry_date','>', Carbon::now()->format('Y-m-d'))->get()->count();
        $stats['total_pending_requests'] = InitialPractice::where('status','Pending')->get()->count();
        $stats['total_accepted_requests'] = Practice::where('status','Accepted')->get()->count();

        return $this->response(true, $stats, SuperAdminGlobalMessageBook::SUCCESS['DASHBOARD_STATS'], 200);
    }
}

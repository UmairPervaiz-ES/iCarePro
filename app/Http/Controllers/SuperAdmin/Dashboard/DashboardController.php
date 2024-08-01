<?php

namespace App\Http\Controllers\SuperAdmin\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\SuperAdmin\Interfaces\Dashboard\DashboardInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardInterface $dashboardRepository;
    public function __construct(DashboardInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    /**
     * @OA\Get(
     *      path="/backend/api/superAdmin/dashboard",
     *      operationId="dashboard",
     *      tags={"SuperAdmin"},
     *      security={
     *         {"passport": {}},
     *      },
     *      summary="Dashboard",
     *      description="Displaying stats for super admin",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *  )
     */
    public function dashboard()
    {
        return $this->dashboardRepository->dashboard();
    }

}

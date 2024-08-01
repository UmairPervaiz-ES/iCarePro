<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait IdentifyCreateOrUpdate
{

    /**
     * Description: Add created_by/ updated_by on creating/ updating.
     * 1) Identify auth user
     * 2) Set condition. Set auth key
     * 3) Add practice_id/ created_by on creating
     * 4) Add updated_by on updating
     *
     * @return void
     */

    public static function bootIdentifyCreateOrUpdate()
    {
        $auth_key = "";
        $authDriver = Auth::getDefaultDriver();
        $authDriver == 'api' ? $auth_key = auth()->user()->user_key : 
        ($authDriver == 'practice-api' ? $auth_key = auth()->user()->practice_key :
        ($authDriver == 'doctor-api' ? $auth_key = auth()->user()->doctor_key : 
        ($authDriver == 'patient-api' ? $auth_key = auth()->user()->patient_key : 
        $auth_key = null)));

        if($auth_key == null && $authDriver == 'doctor'){
            $auth_key = auth()->user()->doctor_key;
        }

        // auto-sets values on creation
        static::creating(function ($query) use ($auth_key) {
            $query->created_by =  $auth_key;
        });
        // auto-sets values on update
        static::updating(function ($query) use ($auth_key) {
            $query->updated_by = $auth_key;
        });
    }
}

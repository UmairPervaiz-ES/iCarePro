<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait ConsentFormAuthentication
{

    /**
     * Description: Add created_by/ updated_by on creating/ updating. Global scope get only authenticated user.
     * 1) Identify auth user
     * 2) Set condition. Set auth key
     * 3) Set auth id. If doctor id logged in, Auth id will be practice_id
     * 4) Add practice_id/ created_by on creating
     * 5) Add updated_by on updating
     * 6) Restrict user to access other user data
     *
     * @return void
     */

    public static function bootConsentFormAuthentication()
    {
        $authDriver = Auth::getDefaultDriver();
        $auth_key = '';
        $condition = '';
        $auth_id = '';
        if ($authDriver == 'api') {
            $condition = 'user_id';
            $auth_key = auth()->user()->user_key;
            $auth_id = auth()->user()->id;
        } else if ($authDriver == 'practice-api') {
            $condition = 'practice_id';
            $auth_key = auth()->user()->practice_key;
            $auth_id = auth()->user()->id;
        } else if ($authDriver == 'doctor-api') {
            $condition = 'doctor_id';
            $auth_key = auth()->user()->doctor_key;
            // $auth_id = auth()->user()->practice_id;
        } 
        else if($authDriver == 'patient-api'){
            $condition = 'patient_id';
            $auth_key = auth()->user()->patient_key;
            $auth_id = auth()->user()->practice_key;
        }

        // auto-sets values on creation
        static::creating(function ($query) use ($auth_key, $condition) {
            $query->$condition = auth()->user()->id;
            $query->created_by = $auth_key;
        });
        // auto-sets values on update
        static::updating(function ($query) use ($auth_key) {
            $query->updated_by = $auth_key;
        });
        if($auth_id){
            static::addGlobalScope('practice_id', function (Builder $builder) use ($auth_id) {
                $builder->where('practice_id', $auth_id);
            });
        }

       
    }
}

<?php

namespace App\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait IdentifyAuthUser
{

    /**
     * Description: Add created_by/ updated_by on creating/ updating. Global scope get only authenticated user.
     * 1) Identify auth user
     * 2) Set condition. Set auth key
     * 3) Add practice_id/ created_by on creating
     * 4) Add updated_by on updating
     * 5) Restrict user to access other user data
     *
     * @return void
     */

    public static function bootIdentifyAuthUser()
    {
        $authDriver = Auth::getDefaultDriver();
        $auth_key = '';
        $condition = '';
        if($authDriver == 'api'){
            $condition = 'user_id';
            $auth_key = auth()->user()->user_key;
        }
        else if($authDriver == 'practice-api'){
            $condition = 'practice_id';
            $auth_key = auth()->user()->practice_key;
        }
        else if($authDriver == 'doctor-api'){
            $condition = 'doctor_id';
            $auth_key = auth()->user()->doctor_key;
        }
        else if($authDriver == 'patient-api'){
            $condition = 'patient_id';
            $auth_key = auth()->user()->patient_key;
        }

        // auto-sets values on creation
        static::creating(function ($query) use($auth_key , $condition) {
            $query->$condition = auth()->user()->id;
            $query->created_by = $auth_key;
        });
        // auto-sets values on update
        static::updating(function ($query) use($auth_key) {
            $query->updated_by = $auth_key;
        });

        static::addGlobalScope('practice_id', function (Builder $builder) use($condition) {
            $builder->where($condition, auth()->user()->id);
        });
    }

}

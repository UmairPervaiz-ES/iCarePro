<?php

namespace App\Models\State;

use App\Models\City\City;
use App\Models\Country\Country;
use App\Models\Doctor\DoctorAddress;
use App\Models\Patient\CommonPatientContact;
use App\Models\Patient\PatientContact;
use App\Models\Patient\PatientEmployment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class State extends Model
{
    use HasFactory;

    public function country(): HasOne
    {
        return $this->hasOne(Country::class);
    }
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function doctorAddressCurrentCity(){
        return $this->belongsTo(DoctorAddress::class, 'current_state_id', 'id');
    }

    public function doctorAddressHomeTownCity(){
        return $this->belongsTo(DoctorAddress::class, 'home_town_state_id', 'id');
    }

    public function patient()
    {
        return $this->hasOne(CommonPatientContact::class);
    }
    public function patient_contact()
    {
        return $this->hasOne(PatientContact::class);
    }
    public function patientEmployment(): HasOne
    {
        return $this->hasOne(PatientEmployment::class);
    }
}

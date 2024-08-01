<?php

namespace App\Models\Country;

use App\Models\City\City;
use App\Models\Doctor\DoctorAddress;
use App\Models\Patient\CommonPatientContact;
use App\Models\Patient\PatientContact;
use App\Models\Patient\PatientEmployment;
use App\Models\State\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    use HasFactory;

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function doctorAddressCurrentCountry(){
        return $this->belongsTo(DoctorAddress::class, 'current_country_id', 'id');
    }

    public function doctorAddressHomeTownCountry(){
        return $this->belongsTo(DoctorAddress::class, 'home_town_country_id', 'id');
    }

    public function contact()
    {
        return $this->hasOne(PatientContact::class);
    }

    public function patient()
    {
        return $this->hasOne(CommonPatientContact::class);
    }
    public function patientContact(): HasOne
    {
        return $this->hasOne(PatientContact::class);
    }

    public function patientEmployment(): HasOne
    {
        return $this->hasOne(PatientEmployment::class);
    }
}

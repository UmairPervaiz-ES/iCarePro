<?php

namespace App\Models\City;

use App\Models\Doctor\DoctorAddress;
use App\Models\Patient\CommonPatientContact;
use App\Models\Patient\PatientContact;
use App\Models\Patient\PatientEmployment;
use App\Models\State\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class City extends Model
{
    use HasFactory;

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function doctorAddressCurrentCity(){
        return $this->belongsTo(DoctorAddress::class, 'current_city_id', 'id');
    }

    public function doctorAddressHomeTownCity(){
        return $this->belongsTo(DoctorAddress::class, 'home_town_city_id', 'id');
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

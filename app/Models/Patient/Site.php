<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function patient_vaccine()
    {
        return $this->hasOne(PatientVaccine::class);
    }

}

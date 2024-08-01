<?php

namespace App\Models\Patient;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalDrugCode extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function patientVaccine()
    {
        return $this->hasOne(Patient\PatientVaccine::class);
    }
    public function vaccine()
    {
        return $this->hasOne(Vaccine::class);
    }

}

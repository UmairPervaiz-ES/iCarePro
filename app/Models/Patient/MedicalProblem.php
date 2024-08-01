<?php

namespace App\Models\Patient;

use App\Models\{EPrescription\PrescribedDrug,
    EPrescription\PrescribedLabTest,
    EPrescription\PrescribedProcedure,
    Appointment\Appointment};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalProblem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function patient_medical_problem()
    {
        return $this->hasOne(PatientMedicalProblem::class);
    }

    public function patientFamilyMedicalHistory()
    {
        return $this->hasOne(PatientFamilyMedicalHistory::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function prescribedLabTest()
    {
        return $this->hasOne(PrescribedLabTest::class);
    }

    public function prescribedDrug()
    {
        return $this->hasOne(PrescribedDrug::class);
    }

    public function prescribedProcedure()
    {
        return $this->hasOne(PrescribedProcedure::class);
    }
}

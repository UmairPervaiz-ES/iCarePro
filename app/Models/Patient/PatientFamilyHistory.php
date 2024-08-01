<?php

namespace App\Models\Patient;

use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientFamilyHistory extends Model
{
    use HasFactory, SoftDeletes;
    use IdentifyCreateOrUpdate;

    protected $guarded = [];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function patientFamilyMedicalHistory()
    {
        return $this->belongsTo(PatientFamilyMedicalHistory::class);
    }

    public function patientRelationship()
    {
        return $this->belongsTo(PatientRelationship::class);
    }
}

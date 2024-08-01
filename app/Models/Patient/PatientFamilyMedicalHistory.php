<?php

namespace App\Models\Patient;

use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientFamilyMedicalHistory extends Model
{
    use HasFactory;

    use HasFactory, SoftDeletes;
    use IdentifyCreateOrUpdate;

    protected $guarded = [];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function medicalProblem()
    {
        return $this->belongsTo(MedicalProblem::class);
    }
    public function PatientFamilyHistory(): HasMany
    {
        return $this->hasMany(PatientFamilyHistory::class);
    }

}

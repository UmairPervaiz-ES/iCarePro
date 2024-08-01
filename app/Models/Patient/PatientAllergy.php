<?php

namespace App\Models\Patient;

use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAllergy extends Model
{
    use HasFactory, SoftDeletes;
    use IdentifyCreateOrUpdate;
    protected $guarded = [];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function allergy()
    {
        return $this->belongsTo(Allergy::class);
    }

    public function patientAllergyReaction()
    {
        return $this->hasMany(PatientAllergyReaction::class);
    }


}

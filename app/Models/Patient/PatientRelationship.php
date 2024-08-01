<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientRelationship extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function patientRelationship()
    {
        return $this->hasOne(PatientFamilyHistory::class);
    }
}

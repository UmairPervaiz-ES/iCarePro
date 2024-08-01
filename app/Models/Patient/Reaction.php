<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reaction extends Model
{
    use HasFactory;

    public function PatientAllergyReaction(): HasOne
    {
        return $this->HasOne(PatientAllergyReaction::class);
    }
}

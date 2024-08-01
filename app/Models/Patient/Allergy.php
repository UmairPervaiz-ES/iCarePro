<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function allergy()
    {
        return $this->hasOne(PatientAllergy::class);
    }
}

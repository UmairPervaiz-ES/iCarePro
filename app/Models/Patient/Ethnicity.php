<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ethnicity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function demography()
    {
        return $this->hasOne(PatientDemography::class);
    }
}

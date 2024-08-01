<?php

namespace App\Models\Language;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function demography()
    {
        return $this->hasOne(Patient\PatientDemography::class);
    }
}

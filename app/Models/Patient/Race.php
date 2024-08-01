<?php

namespace App\Models\Patient;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function demography()
    {
        return $this->hasOne(Patient\PatientDemography::class);
    }
}

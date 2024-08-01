<?php

namespace App\Models\Patient;

use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientIdentification extends Model
{
    use HasFactory;
    use IdentifyCreateOrUpdate;

    protected $guarded = [];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
}

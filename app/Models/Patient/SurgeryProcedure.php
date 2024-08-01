<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurgeryProcedure extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function surgicalHistory()
    {
        return $this->hasOne(PatientSurgicalHistory::class);
    }
}

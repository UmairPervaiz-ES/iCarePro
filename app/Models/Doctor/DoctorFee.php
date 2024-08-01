<?php

namespace App\Models\Doctor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DoctorFee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class, 'id','doctor_id');
    }
}

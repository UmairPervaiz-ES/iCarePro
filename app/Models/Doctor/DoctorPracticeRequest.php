<?php

namespace App\Models\Doctor;

use App\Models\Practice\Practice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorPracticeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }

    public function practice(): BelongsTo
    {
        return $this->belongsTo(Practice::class,'practice_id','id');
    }

    
}

<?php

namespace App\Models\Doctor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialization extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function doctorSpecialization(): BelongsTo
    {
        return $this->belongsTo(DoctorSpecialization::class);
    }
}

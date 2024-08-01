<?php

namespace App\Models\Doctor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DoctorSpecialization extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function specializations(): HasMany
    {
        return $this->hasMany(Specialization::class, 'id', 'specialization_id');
    }
}

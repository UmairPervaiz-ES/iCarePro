<?php

namespace App\Models\Doctor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}

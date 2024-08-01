<?php

namespace App\Models\Doctor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSlotDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function doctorSlot(): BelongsTo
    {
        return $this->belongsTo(DoctorSlot::class);
    }
}

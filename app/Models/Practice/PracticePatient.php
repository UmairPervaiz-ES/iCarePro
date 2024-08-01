<?php

namespace App\Models\Practice;

use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PracticePatient extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }
    public function practice(): HasOne
    {
        return $this->hasOne(Practice::class, 'id', 'practice_id');
    }
}

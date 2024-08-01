<?php

namespace App\Models\Patient;

use App\Models\Language\Language;
use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientDemography extends Model
{
    use HasFactory , SoftDeletes;
    use IdentifyCreateOrUpdate;

    protected $guarded = [];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function ethnicity(): BelongsTo
    {
        return $this->belongsTo(Ethnicity::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function race(): BelongsTo
    {
        return $this->belongsTo(Race::class);
    }
}

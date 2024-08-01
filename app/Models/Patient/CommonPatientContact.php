<?php

namespace App\Models\Patient;

use App\Models\City\City;
use App\Models\Country\Country;
use App\Models\State\State;
use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class CommonPatientContact extends Model
{
    use HasFactory ,SoftDeletes;
    use IdentifyCreateOrUpdate;
    protected $guarded = [];


    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

}

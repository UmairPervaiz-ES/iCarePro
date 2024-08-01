<?php

namespace App\Models\Doctor;

use App\Models\City\City;
use App\Models\Country\Country;
use App\Models\State\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorAddress extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function currentCountry()
    {
        return $this->hasOne(Country::class, 'id', 'current_country_id');
    }

    public function currentState()
    {
        return $this->hasOne(State::class, 'id', 'current_state_id');
    }

    public function currentCity()
    {
        return $this->hasOne(City::class, 'id', 'current_city_id');
    }

    public function homeTownCountry()
    {
        return $this->hasOne(Country::class, 'id', 'home_town_country_id');
    }

    public function homeTownState()
    {
        return $this->hasOne(State::class, 'id', 'home_town_state_id');
    }

    public function homeTownCity()
    {
        return $this->hasOne(City::class, 'id', 'home_town_city_id');
    }
}

<?php

namespace App\Models\Practice;

use App\Models\Country\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeAddress extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function practice()
    {
        return $this->belongsTo(\App\Models\Practice\Practice::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}

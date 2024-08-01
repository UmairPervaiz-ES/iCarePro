<?php

namespace App\Models\Insurance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setCompanyAttribute($value)
    {
        if ($value) {
            return $this->attributes['company'] = ucfirst($value);
        }
    }


}

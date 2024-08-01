<?php

namespace App\Models\Patient;

use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientPrivacy extends Model
{
    use HasFactory, SoftDeletes;
    use IdentifyCreateOrUpdate;

    protected $guarded = [];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
}

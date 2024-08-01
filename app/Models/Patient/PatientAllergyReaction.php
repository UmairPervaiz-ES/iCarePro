<?php

namespace App\Models\Patient;

use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientAllergyReaction extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;
    use IdentifyCreateOrUpdate;
    protected $guarded = [];



    public function patientAllergy()
    {
        return $this->belongsTo(PatientAllergy::class);
    }
    public function reaction()
    {
        return $this->belongsTo(Reaction::class);
    }

}

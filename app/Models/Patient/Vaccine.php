<?php

namespace App\Models\Patient;

use App\Models\EPrescription\Manufacture;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vaccine extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function patientVaccine()
    {
        return $this->hasOne(PatientVaccine::class);
    }
    public function manufacture(): HasMany
    {
        return $this->hasMany(Manufacture::class);
    }
    public function nationalDrugCode(): HasMany
    {
        return $this->hasMany(NationalDrugCode::class);
    }

}

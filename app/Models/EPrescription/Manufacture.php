<?php

namespace App\Models\EPrescription;

use App\Models\{Patient\PatientVaccine, Patient\Vaccine};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function patientVaccine()
    {
        return $this->hasOne(PatientVaccine::class);
    }
    public function vaccine()
    {
        return $this->hasOne(Vaccine::class);
    }

    public function drugs(){
        return $this->belongsTo(Drug::class, 'manufacture_id' , 'id');
    }
}

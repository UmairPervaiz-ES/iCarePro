<?php

namespace App\Models\EPrescription;

use App\Models\{Appointment\Appointment, Doctor\Doctor, Patient\MedicalProblem, Patient\Patient, Practice\Practice};
use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrescribedDrug extends Model
{
    use HasFactory, SoftDeletes;
    use IdentifyCreateOrUpdate;
    protected $guarded = [];

    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function practice(){
        return $this->belongsTo(Practice::class);
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function ePrescription(){
        return $this->belongsTo(EPrescription::class , 'appointment_id' , 'appointment_id');
    }

    public function medicalProblem(){
        return $this->belongsTo(MedicalProblem::class);
    }

    public function drugStrength(){
        return $this->belongsTo(DrugStrength::class , 'strength_id' , 'id');
    }

    public function allDrugStrength(){
        return $this->hasMany(DrugStrength::class , 'drug_id' , 'drug_id');
    }

    public function drug(){
        return $this->hasOne(Drug::class , 'id' , 'drug_id');
    }
}

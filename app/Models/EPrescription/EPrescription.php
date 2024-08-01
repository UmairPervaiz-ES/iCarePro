<?php
namespace App\Models\EPrescription;

use App\Models\{Appointment\Appointment, Doctor\Doctor, Patient\Patient, Practice\Practice};
use App\Traits\IdentifyAuthUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EPrescription extends Model
{
    use HasFactory;
    use IdentifyAuthUser;

    protected $guarded = [];

    public function patient(){
        return  $this->belongsTo(Patient::class);
    }

    public function practice(){
        return  $this->belongsTo(Practice::class);
    }

    public function doctor(){
        return  $this->belongsTo(Doctor::class);
    }

    public function appointment(){
        return  $this->belongsTo(Appointment::class);
    }

    public function prescribedDrugs(){
        return  $this->hasMany(PrescribedDrug::class,'appointment_id','appointment_id');
    }

    public function  prescribedLabTests(){
        return  $this->hasMany(PrescribedLabTest::class,'appointment_id','appointment_id');
    }

    public function prescribedProcedures(){
        return  $this->hasMany(PrescribedProcedure::class, 'appointment_id', 'appointment_id');
    }

    public function drugStrength(){
        return $this->belongsTo(DrugStrength::class , 'strength_id' , 'id');
    }

    public function allDrugStrength(){
        return $this->belongsTo(DrugStrength::class , 'drug_id' , 'id');
    }
}

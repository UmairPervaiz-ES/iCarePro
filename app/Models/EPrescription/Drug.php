<?php

namespace App\Models\EPrescription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function prescribedDrug(){
        return $this->belongsTo(PrescribedDrug::class);
    }

    public function drugStrength(){
        return $this->hasMany(DrugStrength::class , 'drug_id' , 'id');
    }

    public function manufacturer(){
        return $this->hasOne(Manufacture::class , 'id' , 'manufacture_id');
    }
}

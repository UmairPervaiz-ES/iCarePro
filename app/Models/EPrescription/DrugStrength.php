<?php

namespace App\Models\EPrescription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrugStrength extends Model
{
    use HasFactory;

    public function drugs(){
        return $this->belongsTo(Drug::class , 'id' , 'drug_id');
    }
}

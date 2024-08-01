<?php

namespace App\Models\Vital;

use App\Models\Patient\Patient;
use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WcVital extends Model
{
    use HasFactory;
    use IdentifyCreateOrUpdate;
    protected $guarded = [];

    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}

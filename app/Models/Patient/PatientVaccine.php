<?php

namespace App\Models\Patient;

use App\Models\EPrescription\Manufacture;
use App\Traits\IdentifyCreateOrUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PatientVaccine extends Model
{
    use HasFactory, SoftDeletes;
    use IdentifyCreateOrUpdate;

    protected $guarded = [];

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
    public function nationalDrugCode()
    {
        return $this->belongsTo(NationalDrugCode::class);
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function manufacture()
    {
        return $this->belongsTo(Manufacture::class);
    }


}

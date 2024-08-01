<?php

namespace App\Models\Appointment;

use App\Models\{Doctor\Doctor,
    Doctor\DoctorSlot,
    EPrescription\EPrescription,
    EPrescription\PrescribedDrug,
    EPrescription\PrescribedLabTest,
    EPrescription\PrescribedProcedure,
    Patient\Patient,
    Practice\Practice, ZoomAppointmentDetail};
use App\Models\Patient\MedicalProblem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctorSlot(): BelongsTo
    {
        return $this->belongsTo(DoctorSlot::class);
    }

    public function practice(): BelongsTo
    {
        return $this->belongsTo(Practice::class);
    }

    public function ePrescription(){
        return $this->hasMany(EPrescription::class);
    }

    public function prescribedLabTest()
    {
        return $this->hasMany(PrescribedLabTest::class);
    }

    public function prescribedDrug()
    {
        return $this->hasMany(PrescribedDrug::class);
    }

    public function prescribedProcedure()
    {
        return $this->hasMany(PrescribedProcedure::class);
    }

    public function zoomDetail()
    {
        return $this->hasOne(ZoomAppointmentDetail::class, 'appointment_id', 'id');
    }
}


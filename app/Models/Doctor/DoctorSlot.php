<?php

namespace App\Models\Doctor;

use App\Models\Appointment\Appointment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DoctorSlot extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function doctorSlotDays(): HasMany
    {
        return $this->hasMany(DoctorSlotDay::class);
    }

    public function days(): HasMany
    {
        return $this->doctorSlotDays();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}

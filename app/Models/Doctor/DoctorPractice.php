<?php

namespace App\Models\Doctor;

use App\Models\DoctorPracticePermissions;
use App\Models\Practice\Practice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Contracts\Role;

class DoctorPractice extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }

    public function practice(): BelongsTo
    {
        return $this->belongsTo(Practice::class,'practice_id','id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class,'role_id','id');
    }

    public function doctorPracticePermissions(): HasMany
    {
        return $this->hasMany(DoctorPracticePermissions::class);
    }
}

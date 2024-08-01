<?php

namespace App\Models\Doctor;

use App\Models\{Appointment\Appointment,
    CalendarSyncUser,
    EPrescription\EPrescription,
    EPrescription\PrescribedDrug,
    EPrescription\PrescribedLabTest,
    EPrescription\PrescribedProcedure,
    Practice\Practice,
    PushNotification\UserPushNotification};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class Doctor extends Authenticatable
{
    use HasFactory,HasApiTokens,HasRoles,Notifiable;
    protected $guard_name = 'doctor-api';
    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->attributes['first_name'] . ' ' . $this->attributes['middle_name'] . ' ' . $this->attributes['last_name'],
        );
    }

    public function practice(): HasOne
    {
        return $this->hasOne(Practice::class, 'id', 'practice_id');
    }

    public function doctorPracticeRequests(): HasMany
    {
        return $this->hasMany(DoctorPracticeRequest::class,'doctor_id','id');
    }

    public function doctorPractices(): HasMany
    {
        return $this->hasMany(DoctorPractice::class,'doctor_id','id');
    }

    public function doctorAddress(): HasOne
    {
        return $this->hasOne(DoctorAddress::class);
    }

    public function doctorSpecializations(): HasMany
    {
        return $this->hasMany(DoctorSpecialization::class);
    }

    public function doctorDocuments(): HasMany
    {
        return $this->hasMany(DoctorDocument::class);
    }

    public function doctorLegalInformation(): HasOne
    {
        return $this->hasOne(DoctorLegalInformation::class);
    }

    public function doctorSlots(): HasMany
    {
        return $this->hasMany(DoctorSlot::class);
    }

    public function doctorOffDays(): HasMany
    {
        return $this->hasMany(DoctorOffDate::class);
    }

    public function doctorFees(): HasMany
    {
        return $this->hasMany(DoctorFee::class);
    }
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
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

    public function google_calendar(): HasOne
    {
        return $this->hasOne(CalendarSyncUser::class,'login_email','primary_email')->where('sync_type', 'google')->select(['id', 'login_email','calendar_email','sync_type', 'status']);
    }

    public function outlook_calendar(): HasOne
    {
        return $this->hasOne(CalendarSyncUser::class,'login_email','primary_email')->where('sync_type', 'outlook')->select(['id', 'login_email','calendar_email','sync_type', 'status']);
    }

    public function userPushNotifications(): HasMany
    {
        return $this->hasMany(UserPushNotification::class, 'user_id','id')->where('guard_name', '=', 'doctor-api');
    }
}

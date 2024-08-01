<?php

namespace App\Models\Practice;

use App\Models\{Appointment\Appointment,
    Department\DepartmentEmployeeType,
    Doctor\DoctorPractice,
    EPrescription\EPrescription,
    EPrescription\PrescribedDrug,
    EPrescription\PrescribedLabTest,
    EPrescription\PrescribedProcedure,
    PushNotification\UserPushNotification,
    Subscription\Subscription,
    User\User};
use App\Models\Subscription\SubscriptionTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Practice extends Authenticatable
{
    use HasFactory,SoftDeletes,HasApiTokens,HasRoles,Notifiable;
    protected $guard_name = 'practice-api';
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'id', 'subscription_id');
    }

    public function practicePatient(): HasMany
    {
        return $this->hasMany(PracticePatient::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(DoctorPractice::class,'practice_id', 'id');
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(User::class,'practice_id', 'id');
    }

    public function alternativeContacts(): HasManyThrough
    {
        return $this->hasManyThrough(PracticeContact::class,InitialPractice::class,'id','practice_registration_request_id');
    }

    public function transactions()
    {
        return $this->hasMany(SubscriptionTransaction::class);
    }

    public function initialPractice(): BelongsTo
    {
        return $this->belongsTo(InitialPractice::class, 'practice_registration_request_id', 'id');
    }

    public function practiceAddress(): HasOne
    {
        return $this->hasOne(PracticeAddress::class);
    }

    public function practiceBillingAddress(): HasOne
    {
        return $this->hasOne(PracticeBillingAddress::class);
    }

    public function practiceDocuments()
    {
        return $this->hasMany(PracticeDocument::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function practicePaymentMethod()
    {
        return $this->hasMany(PracticePaymentMethod::class);
    }

    public function vitals(){
        return $this->hasMany(Vital::class);
    }

    public function ePrescription(){
        return $this->hasMany(EPrescription::class);
    }

    public function prescribedLabTest()
    {
        return $this->hasOne(PrescribedLabTest::class);
    }

    public function prescribedDrug()
    {
        return $this->hasOne(PrescribedDrug::class);
    }

    public function prescribedProcedure()
    {
        return $this->hasOne(PrescribedProcedure::class);
    }

    public function departmentEmployeeType(): HasMany
    {
        return $this->hasMany(DepartmentEmployeeType::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function userPushNotification(): HasMany
    {
        return $this->hasMany(UserPushNotification::class,'user_id','id')->where('guard_name','=','practice-api');
    }
}


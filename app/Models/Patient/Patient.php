<?php

namespace App\Models\Patient;

use App\Models\{Appointment\Appointment,
    CalendarSyncUser,
    EPrescription\EPrescription,
    EPrescription\PrescribedDrug,
    EPrescription\PrescribedLabTest,
    EPrescription\PrescribedProcedure,
    Practice\PracticePatient,
    PushNotification\UserPushNotification,
    Vital\BloodPressureVital,
    Vital\BmiVital,
    Vital\HeartRateVital,
    Vital\HeightVital,
    Vital\InhaledO2Vital,
    Vital\PainScaleVital,
    Vital\PulseVital,
    Vital\RespiratoryRateVital,
    Vital\TemperatureVital,
    Vital\WcVital,
    Vital\WeightVital};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Patient extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $guard_name = 'patient-api';
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

    public function getCreatedAtAttribute($date)
    {
        return $this->attributes['created_at'] = Carbon::parse($date)->format('Y-m-d');
    }

    public function getUpdatedAtAttribute($date)
    {
        return $this->attributes['updated_at'] = Carbon::parse($date)->format('Y-m-d');
    }

    public function patientContact(): HasOne
    {
        return $this->hasOne(PatientContact::class);
    }
    public function practicePatient()
    {
        return $this->hasMany(PracticePatient::class);
    }
    public function authPracticePatient()
    {
        return $this->hasMany(PracticePatient::class)->where('practice_id',auth()->user()->id);
    }

    public function commonContact(): HasMany
    {
        return $this->hasMany(CommonPatientContact::class);
    }

    public function patientEmployment(): HasOne
    {
        return $this->HasOne(PatientEmployment::class);
    }

    public function patientDemography(): HasOne
    {
        return $this->HasOne(PatientDemography::class);
    }

    public function patientIdentification(): HasOne
    {
        return $this->HasOne(PatientIdentification::class);
    }

    public function socialHistory(): HasOne
    {
        return $this->hasOne(PatientSocialHistory::class);
    }

    public function patientPrivacy(): HasOne
    {
        return $this->hasOne(PatientPrivacy::class);
    }

    public function patientAllergy(): HasMany
    {
        return $this->hasMany(PatientAllergy::class);
    }

    public function patientFamilyMedicalHistory(): HasMany
    {
        return $this->hasMany(PatientFamilyMedicalHistory::class);
    }

    public function medicalProblemHistory(): HasMany
    {
        return $this->hasMany(PatientMedicalProblem::class);
    }

    public function surgicalHistory(): HasMany
    {
        return $this->hasMany(PatientSurgicalHistory::class);
    }

    public function patientVaccine(): HasMany
    {
        return $this->hasMany(PatientVaccine::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    public function ePrescription()
    {
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

    // vitals
    public function bloodPressureVital(){
        return $this->hasMany(BloodPressureVital::class);
    }

    public function heightVital(){
        return $this->hasMany(HeightVital::class);
    }

    public function weightVital(){
        return $this->hasMany(WeightVital::class);
    }

    public function heartRateVital(){
        return $this->hasMany(HeartRateVital::class);
    }

    public function pulseVital(){
        return $this->hasMany(PulseVital::class);
    }

    public function respiratoryRateVital(){
        return $this->hasMany(RespiratoryRateVital::class);
    }

    public function temperatureVital(){
        return $this->hasMany(TemperatureVital::class);
    }

    public function painScaleVital(){
        return $this->hasMany(PainScaleVital::class);
    }

    public function inhaledO2Vital(){
        return $this->hasMany(InhaledO2Vital::class);
    }

    public function wcVital(){
        return $this->hasMany(WcVital::class);
    }

    public function bmiVital(){
        return $this->hasMany(BmiVital::class);
    }

    public function google_calendar(): HasOne
    {
        return $this->hasOne(CalendarSyncUser::class,'login_email','email')->where('sync_type', 'google')->select(['id', 'login_email','calendar_email','sync_type', 'status']);
    }

    public function outlook_calendar(): HasOne
    {
        return $this->hasOne(CalendarSyncUser::class,'login_email','email')->where('sync_type', 'outlook')->select(['id', 'login_email','calendar_email','sync_type', 'status']);
    }

    public function userPushNotifications(): HasMany
    {
        return $this->hasMany(UserPushNotification::class,'user_id','id')->where('guard_name','=','patient-api');
    }
}

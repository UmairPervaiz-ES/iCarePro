<?php

namespace App\Providers;

use App\Repositories\ConsentForm\Eloquent\ConsentFormRepository;
use App\Repositories\ConsentForm\Interfaces\ConsentFormRepositoryInterface;
use App\Repositories\Doctor\Eloquent\Appointment\AppointmentRepository as AppointmentAppointmentRepository;
use App\Repositories\Doctor\Eloquent\Auth\AuthRepository as EloquentAuthAuthRepository;
use App\Repositories\Doctor\Eloquent\DoctorRepository;
use App\Repositories\Doctor\Interfaces\Appointment\AppointmentRepositoryInterface as AppointmentAppointmentRepositoryInterface;
use App\Repositories\Doctor\Interfaces\Auth\AuthRepositoryInterface as InterfacesAuthAuthRepositoryInterface;
use App\Repositories\Doctor\Interfaces\DoctorRepositoryInterface;
use App\Repositories\EPrescription\Eloquent\EPrescription\EPrescriptionRepository;
use App\Repositories\EPrescription\Eloquent\Vital\VitalRepository;
use App\Repositories\EPrescription\Interfaces\EPrescription\EPrescriptionRepositoryInterface;
use App\Repositories\EPrescription\Interfaces\Vital\VitalRepositoryInterface;
use App\Repositories\Insurance\Eloquent\Insurance\InsuranceRepository;
use App\Repositories\Insurance\Interfaces\Insurance\InsuranceRepositoryInterface;
use App\Repositories\Patient\Eloquent\Appointment\AppointmentRepository as EloquentAppointmentAppointmentRepository;
use App\Repositories\Patient\Eloquent\Auth\AuthRepository as PatientEloquentAuthAuthRepository;
use App\Repositories\Patient\Eloquent\PatientHistory\PatientHistoryRepository;
use App\Repositories\Patient\Eloquent\Register\RegisterRepository;
use App\Repositories\Patient\Interfaces\Appointment\AppointmentRepositoryInterface as InterfacesAppointmentAppointmentRepositoryInterface;
use App\Repositories\Patient\Interfaces\Auth\AuthRepositoryInterface as PatientInterfacesAuthAuthRepositoryInterface;
use App\Repositories\Patient\Interfaces\PatientHistory\PatientHistoryRepositoryInterface;
use App\Repositories\Patient\Interfaces\Register\RegisterRepositoryInterface;
use App\Repositories\Practice\Eloquent\Appointment\AppointmentRepository;
use App\Repositories\Practice\Eloquent\Auth\AuthRepository as AuthAuthRepository;
use App\Repositories\Practice\Eloquent\Department\DepartmentRepository;
use App\Repositories\Practice\Eloquent\Doctor\DoctorDraftRepository;
use App\Repositories\Practice\Eloquent\Doctor\DoctorRepository as DoctorDoctorRepository;
use App\Repositories\Practice\Eloquent\Initial\InitialRepository;
use App\Repositories\Practice\Eloquent\Patient\PatientRepository;
use App\Repositories\Practice\Eloquent\Role\RoleRepository;
use App\Repositories\Practice\Eloquent\Staff\StaffRepository;
use App\Repositories\Practice\Interfaces\Appointment\AppointmentRepositoryInterface;
use App\Repositories\Practice\Interfaces\Auth\AuthRepositoryInterface as AuthAuthRepositoryInterface;
use App\Repositories\Practice\Interfaces\Department\DepartmentRepositoryInterface;
use App\Repositories\Practice\Interfaces\Doctor\DoctorDraftRepositoryInterface;
use App\Repositories\Practice\Interfaces\Doctor\DoctorRepositoryInterface as DoctorDoctorRepositoryInterface;
use App\Repositories\Practice\Interfaces\Initial\InitialRepositoryInterface;
use App\Repositories\Practice\Interfaces\Patient\PatientRepositoryInterface;
use App\Repositories\Practice\Interfaces\Role\RoleRepositoryInterface;
use App\Repositories\Practice\Interfaces\Staff\StaffRepositoryInterface;
use App\Repositories\Staff\Eloquent\Auth\AuthRepository as StaffAuthRepository;
use App\Repositories\Staff\Interfaces\Auth\AuthRepositoryInterface as StaffAuthRepositoryInterface;
use App\Repositories\Subscription\Eloquent\Subscription\SubscriptionRepository;
use App\Repositories\Subscription\Eloquent\SubscriptionDiscount\SubscriptionDiscountRepository;
use App\Repositories\Subscription\Eloquent\SubscriptionTransaction\SubscriptionTransactionRepository;
use App\Repositories\Subscription\Interfaces\Subscription\SubscriptionRepositoryInterface;
use App\Repositories\Subscription\Interfaces\SubscriptionDiscount\SubscriptionDiscountRepositoryInterface;
use App\Repositories\Subscription\Interfaces\SubscriptionTransaction\SubscriptionTransactionRepositoryInterface;
use App\Repositories\SuperAdmin\Eloquent\Auth\AuthRepository;
use App\Repositories\SuperAdmin\Eloquent\Dashboard\DashboardRepository;
use App\Repositories\SuperAdmin\Eloquent\Practice\PracticeRepository;
use App\Repositories\SuperAdmin\Eloquent\PracticeRequest\PracticeRequestRepository;
use App\Repositories\SuperAdmin\Interfaces\Auth\AuthRepositoryInterface;
use App\Repositories\SuperAdmin\Interfaces\Dashboard\DashboardInterface;
use App\Repositories\SuperAdmin\Interfaces\Practice\PracticeRepositoryInterface;
use App\Repositories\SuperAdmin\Interfaces\PracticeRequest\PracticeRequestRepositoryInterface;
use App\Repositories\PatientPortal\Eloquent\PatientPortalRepository;
use App\Repositories\PatientPortal\Interfaces\PatientPortalRepositoryInterface;
use App\Repositories\Staff\Eloquent\Appointment\AppointmentRepository as StaffEloquentAppointmentAppointmentRepository;
use App\Repositories\Staff\Interfaces\Appointment\AppointmentRepositoryInterface as StaffInterfacesAppointmentAppointmentRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // SuperAdmin Repos starts

        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(PracticeRequestRepositoryInterface::class, PracticeRequestRepository::class);
        $this->app->bind(DashboardInterface::class, DashboardRepository::class);
        $this->app->bind(PracticeRepositoryInterface::class, PracticeRepository::class);

        // SuperAdmin Repos ends

        $this->app->bind(InitialRepositoryInterface::class, InitialRepository::class);
        $this->app->bind(AuthAuthRepositoryInterface::class, AuthAuthRepository::class);

        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
        $this->app->bind(PatientHistoryRepositoryInterface::class, PatientHistoryRepository::class);
        $this->app->bind(DoctorRepositoryInterface::class, DoctorRepository::class);
        $this->app->bind(AuthAuthRepositoryInterface::class, AuthAuthRepository::class);

        $this->app->bind(SubscriptionRepositoryInterface::class , SubscriptionRepository::class);
        $this->app->bind(SubscriptionDiscountRepositoryInterface::class , SubscriptionDiscountRepository::class);
        $this->app->bind(SubscriptionTransactionRepositoryInterface::class , SubscriptionTransactionRepository::class);
        $this->app->bind(EPrescriptionRepositoryInterface::class , EPrescriptionRepository::class);
        $this->app->bind(VitalRepositoryInterface::class , VitalRepository::class);

        $this->app->bind(DoctorDoctorRepositoryInterface::class , DoctorDoctorRepository::class);
        $this->app->bind(DoctorDraftRepositoryInterface::class , DoctorDraftRepository::class);
        $this->app->bind(InterfacesAuthAuthRepositoryInterface::class, EloquentAuthAuthRepository::class);
        $this->app->bind(AppointmentAppointmentRepositoryInterface::class, AppointmentAppointmentRepository::class);

        $this->app->bind(PatientInterfacesAuthAuthRepositoryInterface::class, PatientEloquentAuthAuthRepository::class);
        $this->app->bind(InterfacesAppointmentAppointmentRepositoryInterface::class, EloquentAppointmentAppointmentRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        $this->app->bind(ConsentFormRepositoryInterface::class, ConsentFormRepository::class);
        $this->app->bind(InsuranceRepositoryInterface::class, InsuranceRepository::class);
        $this->app->bind(PatientRepositoryInterface::class, PatientRepository::class);

        $this->app->bind(PatientPortalRepositoryInterface::class, PatientPortalRepository::class);

        // Staff related repositories and interfaces present in staff directory
        $this->app->bind(StaffAuthRepositoryInterface::class, StaffAuthRepository::class);
        $this->app->bind(StaffInterfacesAppointmentAppointmentRepositoryInterface::class, StaffEloquentAppointmentAppointmentRepository::class);
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}

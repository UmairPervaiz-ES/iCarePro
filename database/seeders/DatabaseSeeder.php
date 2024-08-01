<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(SubscriptionTransactionTableSeeder::class);
        $this->call(PracticeSeeder::class);
        $this->call(ReactionSeeder::class);
        $this->call(VaccineSeeder::class);
        $this->call(AllergySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(EthnicitySeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(ManufactureSeeder::class);
        $this->call(MedicalProblemSeeder::class);
        $this->call(GeneralMedicalProblemSeeder::class);
        $this->call(NationalDrugCodeSeeder::class);
        $this->call(PatientRelationshipSeeder::class);
        $this->call(RaceSeeder::class);
        $this->call(RouteSeeder::class);
        $this->call(SiteSeeder::class);
        $this->call(SurgeryProcedureSeeder::class);
        $this->call(DrugSeeder::class);
        $this->call(DrugStrengthSeeder::class);
        $this->call(ConsentFormTypeSeeder::class);
        $this->call(ConsentFormSeeder::class);
        $this->call(SpecializationSeeder::class);
        $this->call(DoctorSeeder::class);
        $this->call(ZoomCredentialsSeeder::class);
    }
}

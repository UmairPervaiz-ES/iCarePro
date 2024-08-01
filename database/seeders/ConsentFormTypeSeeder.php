<?php

namespace Database\Seeders;

use App\Models\ConsentForm\ConsentFormType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsentFormTypeSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConsentFormType::truncate();

        // Doctor Registration Types
        ConsentFormType::create([
            'category' => 'DOCTOR',
            'sub_category' => 'REGISTRATION',
            'type' => 'Terms and condition',
            'is_required' => 'true',
            'practice_id' => 1,
            'created_by' => 'practicenew1133',
        ], 200);
        ConsentFormType::create([
            'category' => 'DOCTOR',
            'sub_category' => 'REGISTRATION',
            'type' => 'Privacy Policy',
            'is_required' => 'true',
            'practice_id' => 1,
            'created_by' => 'practicenew1133',
        ], 200);
        ConsentFormType::create([
            'category' => 'DOCTOR',
            'sub_category' => 'REGISTRATION',
            'type' => 'GDPR',
            'is_required' => 'true',
            'practice_id' => 1,
            'created_by' => 'practicenew1133',
        ], 200);

        // Registration Registration Types
        ConsentFormType::create([
            'category' => 'PATIENT',
            'sub_category' => 'REGISTRATION',
            'type' => 'Terms and condition',
            'is_required' => 'true',
            'practice_id' => 1,
            'created_by' => 'practicenew1133',
        ], 200);
        ConsentFormType::create([
            'category' => 'PATIENT',
            'sub_category' => 'REGISTRATION',
            'type' => 'Privacy Policy',
            'is_required' => 'true',
            'practice_id' => 1,
            'created_by' => 'practicenew1133',
        ], 200);
        ConsentFormType::create([
            'category' => 'PATIENT',
            'sub_category' => 'REGISTRATION',
            'type' => 'GDPR',
            'is_required' => 'true',
            'practice_id' => 1,
            'created_by' => 'practicenew1133',
        ], 200);
        ConsentFormType::create([
            'category' => 'DOCTOR',
            'sub_category' => 'REGISTRATION',
            'type' => 'GDPR',
            'is_required' => 'true',
            'practice_id' => 1,
            'created_by' => 'practicenew1133',
        ], 200);
    }
}

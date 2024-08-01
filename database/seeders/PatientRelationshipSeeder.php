<?php

namespace Database\Seeders;

use App\Models\Patient\PatientRelationship;
use Illuminate\Database\Seeder;

class PatientRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PatientRelationship::create([
            'relationship' => 'Brother',
        ], 200);

        PatientRelationship::create([
            'relationship' => 'Sister',
        ], 200);

        PatientRelationship::create([
            'relationship' => 'Father',
        ], 200);

        PatientRelationship::create([
            'relationship' => 'Mother',
        ], 200);

        PatientRelationship::create([
            'relationship' => 'Doughter',
        ], 200);

        PatientRelationship::create([
            'relationship' => 'Son',
        ], 200);

        PatientRelationship::create([
            'relationship' => 'Meteranl- Aunt',
        ], 200);

        PatientRelationship::create([
            'relationship' => 'Undefind Relation',
        ], 200);
    }
}

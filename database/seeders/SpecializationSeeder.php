<?php

namespace Database\Seeders;

use App\Models\Doctor\Specialization;
use Illuminate\Database\Seeder;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Specialization::truncate();

        Specialization::create([
            'name' => 'Dermatology',
        ]);

        Specialization::create([
            'name' => 'Neurology',
        ]);

        Specialization::create([
            'name' => 'Internal medicine',
        ]);

        Specialization::create([
            'name' => 'Infectious disease',
        ]);

        Specialization::create([
            'name' => 'Ophthalmologist',
        ]);

        Specialization::create([
            'name' => 'Obstetrician/gynecologist',
        ]);

        Specialization::create([
            'name' => 'Cardiologist',
        ]);

        Specialization::create([
            'name' => 'Endocrinologist',
        ]);

        Specialization::create([
            'name' => 'Gastroenterologist',
        ]);

        Specialization::create([
            'name' => 'Nephrologist',
        ]);
    }
}

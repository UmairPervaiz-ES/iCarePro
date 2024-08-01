<?php

namespace Database\Seeders;

use App\Models\Patient\MedicalProblem;
use Illuminate\Database\Seeder;

class MedicalProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicalProblem::truncate();

        $medical_problems = json_decode(file_get_contents(storage_path('app') . "/medical_problems.json"));
        foreach($medical_problems as $item){
            MedicalProblem::create([
                'name' => $item->name,
                'is_diagnosable' => $item->is_diagnosable,
                'has_laterality' => $item->has_laterality,
                'is_general' => null,
            ], 200);
        }
    }
}

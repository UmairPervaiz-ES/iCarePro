<?php

namespace Database\Seeders;

use App\Models\Patient\SurgeryProcedure;
use Illuminate\Database\Seeder;

class SurgeryProcedureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SurgeryProcedure::truncate();

        $csvFile = fopen(storage_path('app') . '/surgical_history.csv' , "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                SurgeryProcedure::create([
                    "surgery_name" => $data['1'],
                    "has_laterality" => $data['2']
                ]);
            }
            $firstline = false;
        }

        fclose($csvFile);

    }
}

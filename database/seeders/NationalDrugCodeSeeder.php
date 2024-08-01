<?php

namespace Database\Seeders;

use App\Models\Patient\NationalDrugCode;
use Illuminate\Database\Seeder;

class NationalDrugCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NationalDrugCode::truncate();

        $csvFile = fopen(storage_path('app') . '/vaccine_ndc.csv' , "r");

        $first_line = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$first_line) {
                NationalDrugCode::create([
                    "ndc" => $data['1'],
                    "name" => $data['2'],
                    "value" => $data['3'],
                    "formatted_ndc" => $data['4'],
                    "label_name" => $data['5'],
                    "description" => $data['6'],
                    "obsolete_date" => $data['7']
                ]);
            }
            $first_line = false;
        }

        fclose($csvFile);


    }
}

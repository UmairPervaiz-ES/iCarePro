<?php

namespace Database\Seeders;

use App\Models\EPrescription\DrugStrength;
use Illuminate\Database\Seeder;

class DrugStrengthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DrugStrength::truncate();

        $csvFile = fopen(storage_path('app') . '/drug_strength.csv' , "r");

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
                DrugStrength::create([
                    "drug_id" => $data['0'],
                    "drug_strength" => $data['1'],
                ]);
        }
    }
}

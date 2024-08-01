<?php

namespace Database\Seeders;

use App\Models\EPrescription\Drug;
use Illuminate\Database\Seeder;

class DrugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $csvFile = fopen(storage_path('app') . '/drugs.csv' , "r");

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
                Drug::create([
                    // "id" => $data['0'],
                    "name" => $data['1'],
                    "type" => $data['2'],
                    "unit" => $data['3'],
                    "intake" => $data['4'],
                    // "manufacture_id" => $data['6'],
                    "salt_name" => $data['5'],
                ]);
        }

        fclose($csvFile);
    }
}

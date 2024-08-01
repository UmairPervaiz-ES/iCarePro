<?php

namespace Database\Seeders;

use App\Models\Patient\Vaccine;
use Illuminate\Database\Seeder;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vaccine::truncate();

        $csvFile = fopen(storage_path('app') . '/vaccines.csv' , "r");

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
                Vaccine::create([
                    // "id" => $data['0'],
                    "name" => $data['1'],
                ]);
        }

        fclose($csvFile);
    }
}

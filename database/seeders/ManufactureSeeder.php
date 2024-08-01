<?php

namespace Database\Seeders;

use App\Models\EPrescription\Manufacture;
use Illuminate\Database\Seeder;

class ManufactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manufacture::truncate();

        $csvFile = fopen(storage_path('app') . '/drug_manufacturer.csv' , "r");

        $i = 1;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
                Manufacture::create([
                    'id' => $i,
                    "name" => $data['1'],
                ]);
            $i++;
        }
    }
}

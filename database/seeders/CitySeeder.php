<?php

namespace Database\Seeders;

use App\Models\City\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::truncate();

        $csvFile = fopen(storage_path('app') . '/cities.csv' , "r");

        $first_line = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$first_line) {
                City::create([
                    "id" => $data['0'],
                    "state_id" => $data['1'],
                    "name" => $data['2'],
                ]);
            }
            $first_line = false;
        }

        fclose($csvFile);
    }

}

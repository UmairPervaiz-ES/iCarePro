<?php

namespace Database\Seeders;

use App\Models\Country\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::truncate();

        $csvFile = fopen(storage_path('app') . '/countries.csv' , "r");

        $first_line = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$first_line) {
                 $country = Country::create([
                    "id" => $data['0'],
                    "name" => $data['1'],
                    "short_name" => $data['2'],
                    "phone_code" => $data['3'],
                ]);
                 if ($country->name == 'Pakistan' || $country->name == 'United Arab Emirates' || $country->name == 'United States')
                 {
                     $country->update(['is_active' => 1]);
                 }
            }
            $first_line = false;
        }

        fclose($csvFile);

    }
}

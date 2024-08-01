<?php

namespace Database\Seeders;

use App\Models\State\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      State::truncate();

      $csvFile = fopen(storage_path('app') . '/states.csv' , "r");

      $first_line = true;
      while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
          if (!$first_line) {
            State::create([
                  "id" => $data['0'],
                  "country_id" => $data['1'],
                  "name" => $data['2'],
              ]);
          }
          $first_line = false;
      }

      fclose($csvFile);

    }
}

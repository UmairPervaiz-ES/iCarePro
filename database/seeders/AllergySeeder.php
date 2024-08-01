<?php

namespace Database\Seeders;

use App\Models\Patient\Allergy;
use Illuminate\Database\Seeder;

class AllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Allergy::truncate();

        $allergies = json_decode(file_get_contents(storage_path('app') . "/allergies.json"));
        foreach($allergies as $item){
            Allergy::create([
                'name' => $item->name,
            ], 200);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Language\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::create([
            'language' => 'English',
        ], 200);

        Language::create([
            'language' => 'Urdu',
        ], 200);

        Language::create([
            'language' => 'Arabic',
        ], 200);

        Language::create([
            'language' => 'Hindi',
        ], 200);
    }
}

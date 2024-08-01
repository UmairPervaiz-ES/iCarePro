<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::INSERT("INSERT INTO races (name) VALUES
        ('African American'),
        ('American Indian'),
        ('Asian'),
        ('Black or African American'),
        ('English'),
        ('Other Race'),
        ('White');");

    }
}

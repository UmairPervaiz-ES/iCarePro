<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EthnicitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::INSERT("INSERT INTO ethnicities (name) VALUES
        ('Central American'),
        ('Cuban'),
        ('Dominican'),
        ('Hispanic or Latino / Spanish'),
        ('Latin American / Latin, Latino'),
        ('Mexican'),
        ('Not Hispanic Or Latino'),
        ('Puerto Rican'),
        ('Spaniard');");
     
        
    }
}

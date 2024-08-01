<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::INSERT("INSERT INTO reactions (name) VALUES
      ('abdominal pain'),
      ('anaphylaxis'),
      ('angioedema'),
      ('arthralgia (joint pain)'),
      ('bradycardia'),
      ('chest pain'),
      ('confusion'),
      ('cough'),
      ('decreased blood pressure'),
      ('diarrhea'),
      ('dizziness'),
      ('dry mouth'),
      ('dyspnea'),
      ('edema'),
      ('encephalitis'),
      ('eye redness'),
      ('eye swelling'),
      ('facial swelling'),
      ('fever'),
      ('flushing'),
      ('gi bleed'),
      ('hair loss'),
      ('hallucinations'),
      ('headache'),
      ('hives'),
      ('insomnia'),
      ('irregular heart rate'),
      ('itching'),
      ('lightheadedness'),
      ('muscle cramps'),
      ('myalgias (muscle pain)'),
      ('nausea'),
      ('other'),
      ('palpitations'),
      ('photosensitivity'),
      ('rash'),
      ('respiratory distress'),
      ('ringing in ears'),
      ('seizure'),
      ('stevens-johnson syndrome'),
      ('swelling'),
      ('tachycardia'),
      ('vasculitis'),
      ('vomiting'),
      ('wheezing');");



    
    }
}

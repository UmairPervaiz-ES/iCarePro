<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::INSERT("INSERT INTO routes (name) VALUES
      ('Buccal'),
      ('Dental'),
      ('Epidural'),
      ('Hemodialysis'),
      ('Implant'),
      ('In Vitro'),
      ('Inhalation'),
      ('Injection'),
      ('Intra-arterial'),
      ('Intra-articular'),
      ('Intra-cavernosal'),
      ('Intra-urethral'),
      ('Intradermal'),
      ('Intramuscular'),
      ('Intranasal'),
      ('Intraocular'),
      ('Intraperitoneal'),
      ('Intrapleural'),
      ('Intrathecal'),
      ('Intrauterine'),
      ('Intravenous'),
      ('Intravesical'),
      ('Irrigation'),
      ('Miscellaneous'),
      ('Mucous Membrane'),
      ('Ophthalmic'),
      ('Oral'),
      ('Otic'),
      ('Perfusion'),
      ('Rectal'),
      ('Subcutaneous'),
      ('Sublingual'),
      ('Topical'),
      ('Transdermal'),
      ('Translingual'),
      ('Vaginal');");

    }
}

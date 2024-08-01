<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::INSERT("INSERT INTO sites (name) VALUES
        ('Abdomen, LLQ'),
        ('Abdomen, LUQ'),
        ('Abdomen, RLQ'),
        ('Abdomen, RUQ'),
        ('Ankle, Left'),
        ('Ankle, Right'),
        ('Antecubital Fossa, Left'),
        ('Antecubital Fossa, Right'),
        ('Arm, Left Upper'),
        ('Arm, Right Upper'),
        ('Bladder'),
        ('Buttock, Left'),
        ('Buttock, Right'),
        ('Chest Port, Left'),
        ('Chest Port, Right'),
        ('Chest, Left'),
        ('Chest, Right'),
        ('Deltoid, Left'),
        ('Deltoid, Right'),
        ('Dorsogluteal, Left'),
        ('Dorsogluteal, Right'),
        ('Ear, Left'),
        ('Ear, Right'),
        ('Elbow, Left'),
        ('Elbow, Right'),
        ('Eye, Left'),
        ('Eye, Right'),
        ('Foot, Left'),
        ('Foot, Right'),
        ('Forearm, Left'),
        ('Forearm, Right'),
        ('Hand, Left'),
        ('Hand, Right'),
        ('Hip, Left'),
        ('Hip, Right'),
        ('Knee, Left'),
        ('Knee, Right'),
        ('Nasal'),
        ('Oral'),
        ('Other'),
        ('Penis'),
        ('Rectal'),
        ('Rectus femoris, Left'),
        ('Rectus femoris, Right'),
        ('Scrotum'),
        ('Shoulder, Left'),
        ('Shoulder, Right'),
        ('Thigh, Left'),
        ('Thigh, Right'),
        ('Thumb, Left'),
        ('Thumb, Right'),
        ('Vaginal'),
        ('Uterus'),
        ('Vastus Lateralis, Left'),
        ('Vastus Lateralis, Right'),
        ('Ventrogluteal, Left'),
        ('Ventrogluteal, Right'),
        ('Wrist, Left'),
        ('Wrist, Right'),
        ('Left Arm'),
        ('Right Arm'),
        ('Right Upper Arm'),
        ('Left Upper Arm'),
        ('Left Leg'),
        ('Right Leg'),
        ('Left ACF'),
        ('Right ACF'),
        ('Abdomen'),
        ('Left Lower Thigh'),
        ('Right Lower Thigh'),
        ('Left Upper Thigh'),
        ('Right Upper Thigh'),
        ('Right Upper Extremity'),
        ('Left Lower Extremity'),
        ('Left Upper Extremity'),
        ('Right Lower Extremity'),
        ('INTRA ARTICULAR JOINT'),
        ('Left Abdomen'),
        ('Right Abdomen'),
        ('Bilateral Knee'),
        ('Bilateral Thigh'),
        ('Left Upper Eyelid'),
        ('Right Lower Eyelid'),
        ('Right Upper Eyelid'),
        ('Left Lower Eyelid'),
        ('Left Flank'),
        ('Right Flank');");
  
    }
}

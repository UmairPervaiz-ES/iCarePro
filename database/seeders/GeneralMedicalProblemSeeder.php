<?php

namespace Database\Seeders;

use App\Models\Patient\MedicalProblem;
use Illuminate\Database\Seeder;

class GeneralMedicalProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 'Chronic' , 'Acute'
        $general_medical_problems = [
            ['name' => 'Acute bronchitis' , 'type' => 'Acute'],
            ['name' => 'Acute maxillary sinusitis', 'type' => 'Acute'],
            ['name' => 'Allergic rhinitis', 'type' => 'Acute'],
            ['name' => 'Anxiety', 'type' => 'Chronic'],
            ['name' => 'Asthma', 'type' => 'Chronic'],
            ['name' => 'Back pain', 'type' => 'Chronic'],
            ['name' => 'B/L TAH-BSO', 'type' => 'Acute'],
            ['name' => 'Cholecystectomy', 'type' => 'Acute'],
            ['name' => 'Chronic Hepatitis B', 'type' => 'Chronic'],
            ['name' => 'Chronic Hepatitis C', 'type' => 'Chronic'],
            ['name' => 'Depressive disorder', 'type' => 'Chronic'],
            ['name' => 'Diabetes', 'type' => 'Chronic'],
            ['name' => 'Gestational Diabetes Mellitus', 'type' => 'Chronic'],
            ['name' => 'Hakeem Medication', 'type' => 'Acute'],
            ['name' => 'HIV', 'type' => 'Chronic'],
            ['name' => 'Hyperlipidemia', 'type' => 'Chronic'],
            ['name' => 'Hypertension', 'type' => 'Chronic'],
            ['name' => 'Hypothyroidism', 'type' => 'Chronic'],
            ['name' => 'Malaise and fatigue', 'type' => 'Acute'],
            ['name' => 'Obesity', 'type' => 'Chronic'],
            ['name' => 'Osteoarthritis', 'type' => 'Chronic'],
            ['name' => 'PCI', 'type' => 'Acute'],
            ['name' => 'Pulmonary Tuberculosis', 'type' => 'Acute'],
            ['name' => 'Reflux esophagitis', 'type' => 'Chronic'],
            ['name' => 'Respiratory problems', 'type' => 'Acute'],
            ['name' => 'SARS-CoV 2', 'type' => 'Acute'],
            ['name' => 'Steroid Intake', 'type' => 'Chronic'],
            ['name' => 'T. B', 'type' => 'Acute'],
            ['name' => 'Total Thyroidectomy', 'type' => 'Chronic'],
            ['name' => 'Typhoid', 'type' => 'Acute'],
            ['name' => 'Urinary Tract Infection', 'type' => 'Acute'],
            ['name' => 'Visual refractive errors', 'type' => 'Chronic'],
            ['name' => 'Amenorrhea', 'type' => 'Chronic'],
            ['name' => 'Antenatal Care', 'type' => 'Chronic'],
            ['name' => 'Chest pain', 'type' => 'Acute'],
            ['name' => 'ED', 'type' => 'Chronic'],
            ['name' => 'Features of Hyper-Androgenism', 'type' => 'Chronic'],
            ['name' => 'Hirsutism', 'type' => 'Chronic'],
            ['name' => 'Nausea', 'type' => 'Chronic'],
            ['name' => 'Pain LHC', 'type' => 'Chronic'],
            ['name' => 'Pain right flank', 'type' => 'Chronic'],
            ['name' => 'Pen orbital edema', 'type' => 'Chronic'],
            ['name' => 'Shoulder impingement', 'type' => 'Chronic'],
            ['name' => 'Unintentional weight loss', 'type' => 'Chronic'],
            ['name' => 'Antepartum haemorrhage', 'type' => 'Chronic'],
            ['name' => 'Cough', 'type' => 'Chronic'],
            ['name' => 'Epigastric burning', 'type' => 'Chronic'],
            ['name' => 'Fever', 'type' => 'Acute'],
            ['name' => 'Insomnia', 'type' => 'Chronic'],
            ['name' => 'Neck stiffness', 'type' => 'Acute'],
            ['name' => 'Pain lumbar region', 'type' => 'Chronic'],
            ['name' => 'Palpitations', 'type' => 'Acute'],
            ['name' => 'Peripheral neuropathy', 'type' => 'Chronic'],
            ['name' => 'Sleepiness', 'type' => 'Chronic'],
            ['name' => 'Urinary incontinence', 'type' => 'Chronic'],
            ['name' => 'Abnormal hair growth', 'type' => 'Chronic'],
            ['name' => 'Baldness', 'type' => 'Chronic'],
            ['name' => 'Depression', 'type' => 'Chronic'],
            ['name' => 'Epigastric pain', 'type' => 'Acute'],
            ['name' => 'Goiter', 'type' => 'Chronic'],
            ['name' => 'Leg pain', 'type' => 'Acute'],
            ['name' => 'Oral thrush', 'type' => 'Acute'],
            ['name' => 'Pain RHC', 'type' => 'Acute'],
            ['name' => 'Palpitations', 'type' => 'Acute'],
            ['name' => 'PME', 'type' => 'Chronic'],
            ['name' => 'Sore throat', 'type' => 'Acute'],
            ['name' => 'Vertigo', 'type' => 'Acute'],
            ['name' => 'Acne', 'type' => 'Chronic'],
            ['name' => 'Bladder pain', 'type' => 'Acute'],
            ['name' => 'Diarrhea', 'type' => 'Acute'],
            ['name' => 'Fatigue', 'type' => 'Chronic'],
            ['name' => 'Headache', 'type' => 'Acute'],
            ['name' => 'Menstrual irregularities', 'type' => 'Chronic'],
            ['name' => 'Pain left flank', 'type' => 'Acute'],
            ['name' => 'Pain RIF', 'type' => 'Acute'],
            ['name' => 'Pedal edema', 'type' => 'Chronic'],
            ['name' => 'Short Stature', 'type' => 'Chronic'],
            ['name' => 'Unintentional weight gain', 'type' => 'Chronic'],
            ['name' => 'Vomiting', 'type' => 'Acute'],
        ];

        foreach($general_medical_problems as $item){
            MedicalProblem::create([
                'name' => $item['name'],
                'type' => $item['type'],
                'is_diagnosable' => false,
                'has_laterality' => false,
                'is_general' => true,
            ], 200);
        }
    }
}

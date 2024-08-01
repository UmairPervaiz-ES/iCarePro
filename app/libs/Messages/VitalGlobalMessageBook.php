<?php

namespace App\libs\Messages;

class VitalGlobalMessageBook
{
    const FAILED = [
      'APPOINTMENT_NOT_FOUND' => 'No appointment found.'
    ];

    const SUCCESS = [
     'BLOOD_PRESSURE_VITAL_SET' => 'Blood pressure saved.',
     'HEIGHT_VITAL_SET' => 'Height saved.',
     'WEIGHT_VITAL_SET' => 'Weight saved.',
     'HEART_RATE_VITAL_SET' => 'Heart rate saved.',
     'PULSE_VITAL_SET' => 'Pulse saved.',
     'RESPIRATORY_RATE_VITAL_SET' => 'Respiratory rate saved.',
     'TEMPERATURE_VITAL_SET' => 'Temperature saved.',
     'VITAL_PAIN_SCALE_SET' => 'Pain scale saved.',
     'VITAL_INHALED_O2_SET' => 'Inhaled o2 saved.',
     'VITAL_WC_SET' => 'Waist circumference saved.',
     'VITAL_BMI_SET' => 'Bmi saved.',
     'GET_VITAL' => 'Vitals received.',
    ];
}

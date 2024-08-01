<?php
$dev_constants = [
    'PG_DB_HOST'        =>  'localhost',
    'PG_DB_PORT'        =>  '5432',
    'PG_DB_DATABASE'    =>  'icarepro',
    'PG_DB_USERNAME'    =>  'umair.pervaiz',
    'PG_DB_PASSWORD'    =>  'ds1234',
    'EMAIL_DOCTOR_LOGIN' => 'https://dev.icarepro.health/doctor/login',
    'DOCTOR_REQUEST_ROUTE' => 'https://dev.icarepro.health/doctor/invitations',
    'EMAIL_PRACTICE_LOGIN' => 'https://dev.icarepro.health/',
    'EMAIL_PRACTICE_REGISTRATION' => 'https://dev.icarepro.health/practice-registration/',
    'EMAIL_SUPER_ADMIN_LOGIN' => 'https://dev.icarepro.health/super-admin/login',
    'EMAIL_INITIAL_REQUEST_REGISTRATION' => 'https://dev.icarepro.health',
    'EMAIL_PRACTICE_REQUEST_REGISTRATION' => 'https://dev.icarepro.health',
    'EMAIL_PATIENT_LOGIN' => 'https://dev-patient.icarepro.health',
    'EMAIL_STAFF_LOGIN'=>'https://dev.icarepro.health/staff/login',

    // dev
    'GOOGLE_DOCTOR_LOGIN'=>'https://dev-doctor.icarepro.health/doctor/appointments',
    'GOOGLE_PATIENT_LOGIN'=>'https://dev-patient.icarepro.health/appointments',
    'DEV_DOCTOR_CALLBACK'=>'https://dev.icarepro.health/backend/google/callback',
    'DEV_PATIENT_CALLBACK'=>'https://dev.icarepro.health/backend/patient/google/callback',
    // QA
    'QA_GOOGLE_DOCTOR_LOGIN'=>'https://qa-doctor.icarepro.health/doctor/appointments',
    'QA_GOOGLE_PATIENT_LOGIN'=>'https://qa-patient.icarepro.health/appointments',
    'QA_DOCTOR_CALLBACK'=>'https://dev-qa.icarepro.health/backend/QAdoctor/google/callback',
    'QA_PATIENT_CALLBACK'=>'https://dev-qa.icarepro.health/backend/QApatient/google/callback',
    // staging
    'STAGING_GOOGLE_DOCTOR_LOGIN'=>'https://staging-doctor.icarepro.health/doctor/appointments',
    'STAGING_GOOGLE_PATIENT_LOGIN'=>'https://staging-patient.icarepro.health/appointments',
    'STAGING_DOCTOR_CALLBACK'=>'https://staging-practice.icarepro.health/backend/Staggingdoctor/google/callback',
    'STAGING_PATIENT_CALLBACK'=>'https://staging-practice.icarepro.health/backend/Staggingpatient/google/callback',

    // production
    'PRODUCTION_GOOGLE_DOCTOR_LOGIN'=>'https://doctor.icarepro.health/doctor/appointments',
    'PRODUCTION_GOOGLE_PATIENT_LOGIN'=>'https://patient.icarepro.health/appointments',
    'PRODUCTION_DOCTOR_CALLBACK'=>'https://practice.icarepro.health/backend/production-doctor/google/callback',
    'PRODUCTION_PATIENT_CALLBACK'=>'https://practice.icarepro.health/backend/production-patient/google/callback',
];

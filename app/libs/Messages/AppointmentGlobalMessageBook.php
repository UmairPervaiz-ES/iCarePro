<?php

namespace App\libs\Messages;

class AppointmentGlobalMessageBook
{

    const FAILED = [
      //  'TEST' => 'Sorry! Your account is not active, kindly verify your account and try to login again',
        'INITIAL_REQUEST_NOT_FOUND'=>'Initial request not found.',
        'SLOT_NOT_FOUND' => 'Slot not found.',
        'APPOINTMENT_NOT_FOUND'=>'Appointment not exist.',
        'PATIENT_NOT_FOUND' => 'Patient list not found.',
        'PRACTICE_NOT_FOUND'=>'Practice not found.',
        'DOCTOR_NOT_FOUND'=>'Doctor not found.',
        'SPECIALIZATION_NOT_FOUND'=>'Specialization not found.',
        'MEDICAL_NOT_FOUND'=>'Medical problem not found.',
        "DOCTOR_LIST_EMPTY"=>"Doctor list not found.",
        "APPOINTMENT_EXIST"=>"Appointment already exist.",
        "DOCTOR_ZOOM_ACCOUNT_FAILED"=>"Zoom account creation failed.",
        'APPOINTMENT_NOT_RESCHEDULE'=>'Appointment not reSchedule.',

    ];
    const SUCCESS = [
        'PRACTICE_CREATE_APPOINTMENT'=>'Appointment created & email sent to doctor and patient.',
        'DOCTOR_SEND_EMAIL'=>'Doctor appointment letter sent to patient.',
        'SEND_EMAIL_DOCTOR'=>"Appointment rescheduled. Email sent to doctor.",
        'SEND_EMAIL_DOC'=>"Appointment created & email send to doctor.",
        'APPOINTMENT_LIST_DOCTOR'=>"Doctor appointment list received.",
        'SLOT_LIST'=>'Slot list received.',
        'PATIENT_LIST' => 'Patient list received.',
        'APPOINTMENT_LIST_PATIENT'=>"Patient appointment list received.",
        'PRACTICE_LIST'=>'Practice list received.',
        'APPOINTMENT_LIST_PRACTICE'=>"Practice appointment list received.",
        'PRACTICE_DASHBOARD_LIST'=>"Practice dashboard stats received.",
        'PRACTICE_PI_CHART'=>"Practice pi chart stats received.",
        'PRACTICE_APPOINTMENT_WEEK_CHART'=>"Practice appointment 1 week graph chart received.",
        'APPOINTMENT_DETAILS'=>"Patient appointment details received.",
        "SPECIALIZATION_LIST"=>"Specialization list received.",
        'DOCTOR_LIST'=>'Doctor are available against this specialization.',
        'PRACTICE_DOCTOR_LIST'=>'Doctor are available against this practice.',
        'MEDICAL_PROBLEM_LIST'=>'Medical problem list received.',
        'DOCTOR_SPECIALIZATION_LIST'=>'Specialization are available against this doctor.',
        'DOCTOR_SLOT_LIST'=>'Slot are available against this doctor.',
        'DOCTOR_ZOOM_ACCOUNT_CREATED'=>'Zoom email sent to your email account. Please activate zoom account.',
        'MEDICAL_PROBLEMS_ADDED' => 'Medical problems added.'
    ];
}

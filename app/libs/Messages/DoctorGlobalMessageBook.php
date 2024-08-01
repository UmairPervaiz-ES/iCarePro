<?php

namespace App\libs\Messages;

class DoctorGlobalMessageBook
{
    const FAILED = [
        'INVALID_CREDENTIALS' => 'Invalid credentials!',
        'INVALID_OTP' => 'Invalid OTP entered.',
        'FEE_NOT_FOUND' => 'No fee found.',
        'cc' => 'Slot already exist.',
        'NOT_FOUND'=>'Doctor pending list is empty.',
        'DOCTOR_NOT_FOUND'=> 'Doctor not found.',
        'DOCTOR_PRESENT' => 'Email id already taken.',
        'REQUEST_ALREADY_SENT' => 'Request already sent.',
        'PRACTICE_NOT_FOUND'=> 'Practice not found.',
        'EMAIL_NOT_FOUND'=>'Email not found.',
        'SLOT_NOT_FOUND'=>'Slot not found.',
        'DOCUMENT_NOT_FOUND'=> 'Document not found.',
        'ENTRY_PRESENT'=>'Slot already exits for these entries.',
        'PASSWORD_MATCH'=>'The new password should not be same as current password.',
        'REQUEST_NOT_FOUND'=>'Request not found.',
        'NO_PRACTICE_REQUESTS'=>'No requests.',
        'ACCOUNT_SUSPENDED'=>'Doctor\'s account is suspended.',
        'NOTIFICATIONS_NOT_FOUND'=>'Notification not found',
    ];
    const SUCCESS = [
        'LOGGED_IN' => 'Logged in successfully.',
        'CHANGE_PASSWORD' => 'Password changed successfully.',
        'PATIENT_LIST' => 'Doctor Patient list received.',
        'ADDED' => 'is added as a doctor successfully.',
        'ADDED_AS_DRAFT' => 'saved information as draft.',
        'DOCTOR_DASHBOARD_STATS'=>"Doctor dashboard stats received.",
        'DOCTOR_PI_CHART'=>"Doctor pi chart stats received.",
        'ADDED_DOCTOR_THROUGH_DRAFT' => 'is added as a doctor through draft.',
        'ADDED_DOCTOR_DRAFT' => 'saved information as draft.',
        'DOCTOR_DETAILS' => 'Doctor details.',
        'UPDATE_DOCTOR_DRAFT' => 'updated information as draft.',
        'REGISTRATION_LINK_SENT_TO_DOCTOR' => 'Link sent to doctor successfully.',
        'REQUEST_DOCTOR_TO_GET_REGISTER' => 'Request to doctor sent successfully.',
        'DOCTOR_PRESENT_ON_PORTAL' => 'Doctor already present in portal.',
        'DOCTOR_OPENED_REGISTRATION_LINK' => ' opened registration link.',
        'PRIMARY_EMAIL_OTP_REQUEST' => 'has requested to update primary email.',
        'UPDATED_FEE_STATUS' => 'has updated fee status.',
        'LIST_OF_FEE' => 'List of doctor fee.',
        'LIST_OF_OFF_DATES' => 'List of off dates.',
        'LIST_OF_DOCTORS' => 'List of doctors.',
        'APPOINTMENT_CANCELED' => 'Canceled an appointment.',
        'APPOINTMENT_CANCEL_EMAIL' => 'Appointment cancel email sent to. ',
        'REGISTER' => 'You are registered successfully as a new admin.',
        'DOCTOR_REQUEST'=>"Response sent by email.",
        'RESET_PASSWORD'=>'Reset password successfully.',
        'PRIMARY_EMAIL_UPDATED_OTP_SENT' => 'OTP sent successfully. Check your email.',
        'PRIMARY_EMAIL_UPDATED_SUCCESSFULLY' => 'Primary email updated successfully.',
        'ADDED_FEE' => 'Fees added successfully.',
        'FEE_STATUS_UPDATED' => 'Fees status updated.',
        'SLOT_ADDED' => 'Slot added successfully.',
        'SLOT_PUBLISHED' => 'Slot published.',
        'ADDED_OFF_DATES' => 'Off dates added successfully.',
        'DOCTOR_OFF_DATES' => 'Doctor off dates.',
        'DELETED_OFF_DATES' => 'Selected dates deleted.',
        'SLOT_DEACTIVATE_STATUS' => 'Slot is deactivated.',
        'DOCUMENT_DELETED' => 'Document deleted successfully.',
        'DOCUMENT_UPLOADED' => 'Document uploaded successfully.',
        'SPECIALIZATION_LIST' => 'List of specializations.',
        'SPECIALIZATION_UPDATED' => 'Specialization(s) updated.',
        'UPDATED_PERSONAL_INFORMATION' => 'Personal information updated successfully.',
        'UPDATED_ABOUT_ME' => 'About me updated successfully.',
        'UPDATED_CONTACT_INFORMATION' => 'Contact information updated successfully.',
        'UPDATED_CURRENT_ADDRESS' => 'Current address updated successfully.',
        'LOGOUT' => 'Logout successfully.',
        'DOCTOR_LIST'=>'Doctor pending list.',
        'LIST_OF_SLOTS' => 'List of slots.',
        'REQUEST_STATUS_UPDATED' => 'Request status updated.',
        'PRACTICE_SWITCHED_SUCCESSFULLY' => 'Practice switched successfully.',
        'PRACTICE_REQUEST_LIST' => 'List of practice requests',

        'CONSENT_FORM_RECEIVED'=>'Consent forms received.',
        'CONSENT_FORM_RESPONSE_RECEIVED'=>'Consent forms response received.',
        'PUBLISHED_CONSENT_FORM_RECEIVED'=>'Published consent forms received.',
        'CONSENT_FORM_TYPE_CREATED'=>'Consent Form type created.',
        'CONSENT_FORM_TYPE_UPDATED'=>'Consent Form type updated.',
        'CONSENT_FORM_CREATED'=>'Consent form created.',
        'CONSENT_FORM_UPDATED'=>'Consent form updated.',
        'DOCTOR_CONSENT_FORM_CREATED'=>'Doctor consent form response saved.',
        'DOCTOR_CONSENT_FORM_UPDATED'=>'Doctor consent form response updated.',
        'REGISTER_DOCTOR_CONSENT_FORM_RECEIVED'=>'Register doctor consent form received.',
        'REGISTER_DOCTOR_PUBLISHED_CONSENT_FORM_RECEIVED'=>'Register doctor published consent form received.',
        'REGISTER_PATIENT_CONSENT_FORM_RECEIVED'=>'Register patient consent form received.',
        'REGISTER_PATIENT_PUBLISHED_CONSENT_FORM_RECEIVED'=>'Register patient published consent form received.',
        'DOCTOR_CALENDAR_APPOINTMENTS'=>'Appointments',
        'NOTIFICATIONS'=>'Notifications',
        'NOTIFICATION_READ'=>'Notifications_read',
        'ALL_NOTIFICATIONS_MARKED_AS_READ'=>'All notifications marked as read',
    ];
}
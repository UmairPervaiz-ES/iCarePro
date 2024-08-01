<?php

namespace App\Repositories\Patient\Eloquent\Auth;

use App\libs\Messages\PatientGlobalMessageBook as PGMBook;
use App\Models\CalendarSyncUser;
use App\Models\Patient\Patient;
use App\Models\Practice\PracticePatient;
use App\Repositories\Patient\Interfaces\Auth\AuthRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
  use RespondsWithHttpStatus;

  /**
   * Description: Login Patient
   * 1) Check patient against phone number. Return if not exist
   * 2) Check patient phone number verified. Return if not verified
   * 3) Check patient is already login. Return if already logged in
   * 4) Check patient credentials. Return if invalid
   * 5) Create token, logged in if valid credentials
   * 6) Activity is logged
   * 7) Return with success message and patient info
   *
   * @param  mixed $request
   * @return Response
   */
  public function login($request)
  {
    //check patient against phone number
    $patient = Patient::where(['country_code' => $request['country_code'] , 'phone_number' => $request['phone_number']])->first();
    $check = false; $status = 401; $success = false;

    if (empty($patient)) {
      $check = true; $message = PGMBook::SUCCESS['YOUR_STATUS'];
    } elseif ($patient->is_phone_number_verified == '0') {
      $check = true; $message = PGMBook::SUCCESS['PATIENT_NUMBER_CHECK'];
    } elseif ($patient->is_login == '0') {
      $check = true; $message = PGMBook::SUCCESS['PATIENT_LOGIN_CHECK'];
    }
    
    if (!$check) {
      // Check credentials is true or false
      if (Auth::guard('patient')->attempt(['country_code' => $request['country_code'] ,'phone_number' => $request['phone_number'], 'password' => $request['password']])) {
        $patient = Auth::guard('patient')->user();
        $patient['token'] =  $patient->createToken('patient')->accessToken;
        $practicePatient = PracticePatient::where('patient_id', $patient['id'])->first();
        if ($practicePatient) {
        $patient['practice_country_name'] = $patient['practicePatient'][0]['practice']['practiceAddress']['country']['name'];
        unset($patient['practicePatient']);
        }
        $patient['outlook_calendar'] = CalendarSyncUser::where('login_email', $patient->email)
        ->whereNotNull('calendar_email')
        ->where('sync_type', 'outlook')->first();
        $patient['google_calendar'] = CalendarSyncUser::where('login_email', $patient->email)
        ->whereNotNull('calendar_email')
        ->where('sync_type', 'google')->first();
        $message = PGMBook::SUCCESS['LOGGED_IN']; $status = 200; $success = true;
      } else {
        $message = PGMBook::FAILED['INVALID_CREDENTIALS']; $patient = false;
      }
    }
    return $this->response($request, $patient, $message, $status, $success);
  }

  /**
   * Description: Logout Patient
   * 1) Revoke patient token
   * 2) Activity logged
   * 3) Return with success
   *
   * @return Response
   */
  public function logout()
  {
    Auth::guard('patient-api')->user()->token()->revoke();

    return $this->response(true, null, PGMBook::SUCCESS['LOGGED_OUT'], 201);
  }
}

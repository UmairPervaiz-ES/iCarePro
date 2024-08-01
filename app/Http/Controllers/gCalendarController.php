<?php

namespace App\Http\Controllers;

use App\Models\CalendarSyncUser;
use App\Models\Doctor\Doctor;
use App\Models\Patient\Patient;
use App\Models\UserCalendar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Auth;

use Svg\Tag\Rect;

class gCalendarController extends Controller
{
    protected $client;

    public function __construct(Request $request)
    {
        $path = $request->getPathInfo();
        $client = new Google_Client();
        if ($path == "/google/callback") {
            $client->setAuthConfig('client_secret.json');
        } elseif ($path == "/patient/google/callback") {
            $client->setAuthConfig('patient_client_secret.json');
        } elseif ($path == "/QApatient/google/callback") {
            $client->setAuthConfig('Qa_patient_client_secret.json');
        } elseif ($path == "/QAdoctor/google/callback") {
            $client->setAuthConfig('Qa_doctor_client_secret.json');
        } elseif ($path == "/Staggingpatient/google/callback") {
        $client->setAuthConfig('Stagging_patient_client_secret.json');
        } elseif ($path == "/Staggingdoctor/google/callback") {
        $client->setAuthConfig('Stagging_doctor_client_secret.json');
        } elseif ($path == "/production-doctor/google/callback") {
        $client->setAuthConfig('production-doctor.json');
        } elseif ($path == "/production-patient/google/callback") {
        $client->setAuthConfig('production-patient.json');
        }

        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setIncludeGrantedScopes(true);
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client = $client;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        session_start();

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);
            $calendarId = 'primary';
            $results = $service->events->listEvents($calendarId);
            $responseData = $results->getItems();
            $i = true;


            $loginEmail = CalendarSyncUser::latest()->first();
            $loginEmail->calendar_email = $results['summary'];
            $loginEmail->status = true;
            $loginEmail->update();

           UserCalendar::where('access_token', $_SESSION['access_token']['access_token'])->update([
                'user_email' => $results['summary'],
                'login_email' =>   $loginEmail->login_email,
            ]);


            $userCalendarUpdated = UserCalendar::where('access_token', $_SESSION['access_token']['access_token'])->first();

            if ($userCalendarUpdated) {

                if ($userCalendarUpdated['url'] == "/google/callback") {
                    return redirect()->away(config('constants.GOOGLE_DOCTOR_LOGIN'));
                } elseif ($userCalendarUpdated['url'] == "/patient/google/callback") {

                    return redirect()->away(config('constants.GOOGLE_PATIENT_LOGIN'));

                } elseif ($userCalendarUpdated['url'] == "/QApatient/google/callback") {
                    return redirect()->away(config('constants.QA_GOOGLE_PATIENT_LOGIN'));


                } elseif ($userCalendarUpdated['url'] == "/QAdoctor/google/callback") {
                    return redirect()->away(config('constants.QA_GOOGLE_DOCTOR_LOGIN'));

                }
                elseif ($userCalendarUpdated['url'] == "/Staggingpatient/google/callback") {
                    return redirect()->away(config('constants.STAGING_GOOGLE_PATIENT_LOGIN'));

                }

                elseif ($userCalendarUpdated['url'] == "/Staggingdoctor/google/callback") {
                    return redirect()->away(config('constants.STAGING_GOOGLE_DOCTOR_LOGIN'));

                }
                elseif ($userCalendarUpdated['url'] == "/production-doctor/google/callback") {
                    return redirect()->away(config('constants.PRODUCTION_GOOGLE_DOCTOR_LOGIN'));

                }
                elseif ($userCalendarUpdated['url'] == "/production-patient/google/callback") {
                    return redirect()->away(config('constants.PRODUCTION_GOOGLE_PATIENT_LOGIN'));

                }
            }
        } else {
            return redirect()->route('oauthCallback');
        }
    }

    public function oauth(Request $request)
    {
        session_start();
        $path = $request->getPathInfo();
        $client = new Google_Client();
        if ($path == "/google/callback") {
            $rurl = config('constants.DEV_DOCTOR_CALLBACK');

        } elseif ($path == "/patient/google/callback") {

            $rurl = config('constants.DEV_PATIENT_CALLBACK');
        } elseif ($path == "/QApatient/google/callback") {

            $rurl = config('constants.QA_PATIENT_CALLBACK');

        } elseif ($path == "/QAdoctor/google/callback") {
            $rurl = config('constants.QA_DOCTOR_CALLBACK');

        }
        elseif ($path == "/Staggingpatient/google/callback") {
            $rurl = config('constants.STAGING_PATIENT_CALLBACK');

        }
        elseif ($path == "/Staggingdoctor/google/callback") {
            $rurl = config('constants.STAGING_DOCTOR_CALLBACK');

        }
        elseif ($path == "/production-doctor/google/callback") {
            $rurl = config('constants.PRODUCTION_DOCTOR_CALLBACK');

        }
        elseif ($path == "/production-patient/google/callback") {
            $rurl = config('constants.PRODUCTION_PATIENT_CALLBACK');
        }
        $this->client->setRedirectUri($rurl);
        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            UserCalendar::create([
                'user_email' => null,
                'access_token' => $_SESSION['access_token']['access_token'],
                'expires_in' => $_SESSION['access_token']['expires_in'],
                'scope' => $_SESSION['access_token']['scope'],
                'token_type' => $_SESSION['access_token']['token_type'],
//                 'refresh_token' => $_SESSION['access_token']['refresh_token'],
                'refresh_token' => isset($_SESSION['access_token']['refresh_token']) ? $_SESSION['access_token']['refresh_token'] : null,
                'id_token' => isset($_SESSION['access_token']['id_token']) ? $_SESSION['access_token']['id_token'] : null,
                'token_updated_at' =>  \Carbon\Carbon::now(),
                'token_updated_by_patient' =>  \Carbon\Carbon::now(),
                'url' => $path,

            ]);

            return redirect()->route('cal.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $patient = Patient::where('id', $request['patient_id'])->first();
        $doctor = Doctor::where('id', $request['doctor_id'])->first();
        date_default_timezone_set('Asia/karachi');
        $start = Carbon::parse($request['date'] . ' ' . $request['start_time'])->toIso8601String();
        $end = Carbon::parse($request['date'] . ' ' . $request['end_time'])->toIso8601String();
        session_start();
        $patientCalendar = UserCalendar::where('login_email', $patient['email'])->first();
        $doctorCalendar = UserCalendar::where('login_email', $doctor['primary_email'])->first();

        if (!empty($doctorCalendar)) {
            if ($doctorCalendar->token_updated_at   <= Carbon::now()->subHour()) {
                self::refreshTokenDoctor($doctorCalendar);
                $client = new Google_Client();
                if ($doctorCalendar['url'] == "/google/callback") {
                    $client->setAuthConfig('client_secret.json');
                } elseif ($doctorCalendar['url'] == "/QAdoctor/google/callback") {
                    $client->setAuthConfig('Qa_doctor_client_secret.json');
                }
                elseif ($doctorCalendar['url'] == "/Staggingdoctor/google/callback") {
                    $client->setAuthConfig('Stagging_doctor_client_secret.json');
                }
                $client->addScope(Google_Service_Calendar::CALENDAR);
                $this->client->setAccessToken($doctorCalendar['access_token']);
                $serviceDoctor = new Google_Service_Calendar($this->client);
                $calendarIdDoctor = 'primary';
                $eventDoctor = new Google_Service_Calendar_Event([
                    'summary' => "Appointment confirmed",
                    'description' => "Your appointment has been confirmed with" . ' ' . $patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name'],
                    'start' => ['dateTime' => $start],
                    'end' => ['dateTime' =>  $end],
                    'reminders' => ['useDefault' => true],
                ]);
                $serviceDoctor->events->insert($calendarIdDoctor, $eventDoctor);
            }
            else {
                $client = new Google_Client();
                if ($doctorCalendar['url'] == "/google/callback") {
                    $client->setAuthConfig('client_secret.json');
                } elseif ($doctorCalendar['url'] == "/QAdoctor/google/callback") {
                    $client->setAuthConfig('Qa_doctor_client_secret.json');
                }
                elseif ($doctorCalendar['url'] == "/Staggingdoctor/google/callback") {
                    $client->setAuthConfig('Stagging_doctor_client_secret.json');
                }
                elseif ($doctorCalendar['url'] == "/production-doctor/google/callback") {
                    $client->setAuthConfig('production-doctor.json');
                }

                // $client->setAuthConfig('client_secret.json');
                $client->addScope(Google_Service_Calendar::CALENDAR);

                $this->client->setAccessToken($doctorCalendar['access_token']);
                $serviceDoctor = new Google_Service_Calendar($this->client);
                $calendarIdDoctor = 'primary';
                $eventDoctor = new Google_Service_Calendar_Event([
                    'summary' => "Appointment confirmed",
                    'description' => "Your appointment has been confirmed with" . ' ' . $patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name'],
                    'start' => ['dateTime' => $start],
                    'end' => ['dateTime' =>  $end],
                    'reminders' => ['useDefault' => true],
                ]);
                $serviceDoctor->events->insert($calendarIdDoctor, $eventDoctor);
            }
        }

        if (!empty($patientCalendar)) {

            if ($patientCalendar->token_updated_by_patient <= Carbon::now()->subHour()) {
                // Token has expired (1 hour)

                self::refreshTokenPatient($patientCalendar);
                 $client = new Google_Client();
                if ($patientCalendar['url'] == "/patient/google/callback") {
                    $client->setAuthConfig('patient_client_secret.json');
                } elseif ($patientCalendar['url'] == "/QApatient/google/callback") {
                    $client->setAuthConfig('Qa_patient_client_secret.json');
                } elseif ($patientCalendar['url'] == "/Staggingpatient/google/callback") {
                    $client->setAuthConfig('Stagging_patient_client_secret.json');
                }
                // $client->setAuthConfig('client_secret.json');
                $client->addScope(Google_Service_Calendar::CALENDAR);
                $client->setAccessToken($patientCalendar['access_token']);

                // gCalendarController::store($request);
                // exit();

                $service = new Google_Service_Calendar($client);
                $calendarId = 'primary';
                $event = new Google_Service_Calendar_Event([
                    'summary' => "Appointment confirmed",
                    'description' => "Your appointment has been confirmed with" . ' ' . $doctor['first_name'] . ' ' . $doctor['middle_name'] . ' ' . $doctor['last_name'],
                    'start' => ['dateTime' =>  $start],
                    'end' => ['dateTime' =>  $end],
                    'reminders' => ['useDefault' => true],
                ]);
                $service->events->insert($calendarId, $event);
            }
            else {
                $client = new Google_Client();
                if ($patientCalendar['url'] == "/patient/google/callback") {
                    $client->setAuthConfig('patient_client_secret.json');
                } elseif ($patientCalendar['url'] == "/QApatient/google/callback") {
                    $client->setAuthConfig('Qa_patient_client_secret.json');
                } elseif ($patientCalendar['url'] == "/Staggingpatient/google/callback") {
                    $client->setAuthConfig('Stagging_patient_client_secret.json');
                } elseif ($patientCalendar['url'] == "/production-patient/google/callback") {
                    $client->setAuthConfig('production-patient.json');
                }
                // $client->setAuthConfig('client_secret.json');
                $client->addScope(Google_Service_Calendar::CALENDAR);
                $client->setAccessToken($patientCalendar['access_token']);
                $service = new Google_Service_Calendar($client);
                $calendarId = 'primary';
                $event = new Google_Service_Calendar_Event([
                    'summary' => "Appointment confirmed",
                    'description' => "Your appointment has been confirmed with" . ' ' . $doctor['first_name'] . ' ' . $doctor['middle_name'] . ' ' . $doctor['last_name'],
                    'start' => ['dateTime' =>  $start],
                    'end' => ['dateTime' =>  $end],
                    'reminders' => ['useDefault' => true],
                ]);
                $service->events->insert($calendarId, $event);
            }
        }
    }

    public static function refreshTokenPatient($patientCalendar)
    {

        if ($patientCalendar['url'] == "/patient/google/callback") {
            $clientID       = '960382014976-bus6831fev2ibq7njke573nmmrtjtj0k.apps.googleusercontent.com';
            $clinetSecret   = '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';
        } elseif ($patientCalendar['url'] == "/QApatient/google/callback") {
            $clientID       = '274329508758-c070fmjvl6n4ndmiqustre3jqih4b27o.apps.googleusercontent.com';
            $clinetSecret   = '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';
        }
        elseif ($patientCalendar['url'] == "/Staggingpatient/google/callback") {
            $clientID       = '1033462572280-srtol1tiu4r9kjies0c661os3hj8sjk2.apps.googleusercontent.com';
            $clinetSecret   = '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';
        }

        $refresh_token  =  $patientCalendar['refresh_token'];
        $endPoint      = 'https://www.googleapis.com/oauth2/v4/token';
        // Testing
        $client = new Google_Client();
        $client->setAccessToken($refresh_token);
        // $this->client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $refresh_token;

        $requestPatient = new Client();
        $responsePatient = $requestPatient->post($endPoint, [
            'form_params' => [
                'client_id' => $clientID,
                'client_secret' => $clinetSecret,
                "grant_type" => "refresh_token",
                "refresh_token" => $refresh_token,
            ],
        ]);
        $responseDataPatient = (json_decode($responsePatient->getBody()->getContents()));
        if ($responseDataPatient) {
            UserCalendar::where('id', $patientCalendar['id'])->update([
                'access_token' => $responseDataPatient->access_token,
                'expires_in' => $responseDataPatient->expires_in,
                'scope' => $responseDataPatient->scope,
                'token_type' =>  $responseDataPatient->token_type,
                'id_token' =>  isset($responseDataPatient->id_token,) ? $responseDataPatient->id_token : null,
                'refresh_token' =>  $refresh_token,
                'token_updated_at' => \Carbon\Carbon::now(),
                'token_updated_by_patient' =>  \Carbon\Carbon::now(),
                'url' => $patientCalendar['url'],

            ]);
        }

    }

    public static function refreshTokenDoctor($doctorCalendar)
    {
        if ($doctorCalendar['url'] == "/google/callback") {
            $clientID       = '183129290208-9maht5rd37bie8p72v87e7th7q3h28i2.apps.googleusercontent.com';
            $clinetSecret   = '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';
        } elseif ($doctorCalendar['url'] == "/QAdoctor/google/callback") {
            $clientID       = '843052042406-9b6hqli2qss97iqal2177s1jnasv4iii.apps.googleusercontent.com';
            $clinetSecret   = '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';
        }
        elseif ($doctorCalendar['url'] == "/Staggingdoctor/google/callback") {
            $clientID       = '892586939996-9kk9tm44su6nqbpm74f1nbbic3sos25g.apps.googleusercontent.com';
            $clinetSecret   = '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';
        }

        $refresh_token =  $doctorCalendar['refresh_token'];
        $endPoint      = 'https://www.googleapis.com/oauth2/v4/token';
        $request = new Client();
        $response = $request->post($endPoint, [
            'form_params' => [
                'client_id' => $clientID,
                'client_secret' => $clinetSecret,
                "grant_type" => "refresh_token",
                "refresh_token" => $refresh_token,
            ],
        ]);
        $responseData = (json_decode($response->getBody()->getContents()));
        if ($responseData) {
            UserCalendar::where('id', $doctorCalendar['id'])->update([
                'access_token' => $responseData->access_token,
                'expires_in' => $responseData->expires_in,
                'scope' => $responseData->scope,
                'token_type' =>  $responseData->token_type,
                'id_token' =>  isset($responseData->id_token,) ? $responseData->id_token : null,
                'refresh_token' =>  $refresh_token,
                'token_updated_at' => \Carbon\Carbon::now(),
                'token_updated_by_patient' =>  \Carbon\Carbon::now(),
                'url' => $doctorCalendar['url'],

            ]);
        }
        //
    }

    public static function getUserEmail(Request $request)
    {
        $loginEmail = $request->email;;
        CalendarSyncUser::where(['login_email' => $loginEmail, 'sync_type' => 'google','status' => 0])->delete();

            CalendarSyncUser::create([

                'login_email' =>   $loginEmail,
                'sync_type' => "google",

            ]);
    }

    public static function getPatientEmail(Request $request)
    {
        $loginEmail = $request->email;
        CalendarSyncUser::where(['login_email' => $loginEmail, 'sync_type' => 'google','status' => 0])->delete();
        CalendarSyncUser::create([

            'login_email' =>   $loginEmail,
            'sync_type' => "google",

        ]);
    }

    public function removeGoogleCalendar(Request $request){

         $loginUser = $request->email;
         UserCalendar::where('login_email',  $loginUser)->delete();
         CalendarSyncUser::where(['login_email' =>  $loginUser , "sync_type" => "google", "status" => true])->delete();
    }

}

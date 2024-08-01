<?php

namespace App\Http\Controllers;

use App\Models\CalendarSyncUser;
use App\Models\Doctor\Doctor;
use App\Models\Patient\Patient;
use App\Models\UserOutlookCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class oCalendarController extends Controller
{

    public function getAuthUrl(Request $request)
    {

        $tenant_id = config('constants.patient_tenant_id');
        $client_id = config('constants.patient_client_id');
        $client_secret = config('constants.patient_client_secret');
        if ($request->getPathInfo() == "/patient/outlook/callback") {
            $callback = config('constants.patient_callback');
            $redirect_uri = config('constants.patient_redirect_uri');
        } elseif ($request->getPathInfo() == "/doctor/outlook/callback") {
            $callback = config('constants.doctor_callback');
            $redirect_uri = config('constants.doctor_redirect_uri');
        } elseif ($request->getPathInfo() == "/QApatient/outlook/callback") {
            $callback = config('constants.QApatient_callback');
            $redirect_uri = config('constants.QApatient_redirect_uri');
        } elseif ($request->getPathInfo() == "/QAdoctor/outlook/callback") {
            $callback = config('constants.QAdoctor_callback');
            $redirect_uri = config('constants.QAdoctor_redirect_uri');
        } elseif ($request->getPathInfo() == "/Staggingpatient/outlook/callback") {
            $callback = config('constants.Staggingpatient_callback');
            $redirect_uri = config('constants.Staggingpatient_redirect_uri');
        } elseif ($request->getPathInfo() == "/Staggingdoctor/outlook/callback") {
            $callback = config('constants.Staggingdoctor_callback');
            $redirect_uri = config('constants.Staggingdoctor_redirect_uri');
        } elseif ($request->getPathInfo() == "/Productionpatient/outlook/callback") {
            $callback = config('constants.Productionpatient_callback');
            $redirect_uri = config('constants.Productionpatient_redirect_uri');
        } elseif ($request->getPathInfo() == "/Productiondoctor/outlook/callback") {
            $callback = config('constants.Productiondoctor_callback');
            $redirect_uri = config('constants.Productiondoctor_redirect_uri');
        }


        $host = "https://login.microsoftonline.com/";
        $scopes = ["User.Read offline_access"];
        $parameters = [
            'client_id' => $client_id,
            'response_type' => 'code',
            'redirect_uri' => $redirect_uri,
            'response_mode' => 'query',
            'scope' => implode(' ', $scopes),
            'grant_type'        => 'authorization_code',
        ];

        UserOutlookCalendar::create([

            'url' => $request->getPathInfo(),
        ]);


        return redirect(trim($host . $tenant_id . "/oauth2/v2.0/authorize?" . http_build_query($parameters)));
    }

    public function callBack(Request $request)
    {
        $userCalendarDetails = UserOutlookCalendar::orderBy('created_at', 'desc')->first();
        $tenant_id = config('constants.patient_tenant_id');
        $client_id = config('constants.patient_client_id');
        $client_secret = config('constants.patient_client_secret');

        if ($userCalendarDetails->url == "/patient/outlook/callback") {
            $callback = config('constants.patient_callback');
            $redirect_uri = config('constants.patient_redirect_uri');
        } elseif ($userCalendarDetails->url == "/doctor/outlook/callback") {
            $callback = config('constants.doctor_callback');
            $redirect_uri = config('constants.doctor_redirect_uri');
        } elseif ($userCalendarDetails->url == "/QApatient/outlook/callback") {
            $callback = config('constants.QApatient_callback');
            $redirect_uri = config('constants.QApatient_redirect_uri');
        } elseif ($userCalendarDetails->url == "/QAdoctor/outlook/callback") {
            $callback = config('constants.QAdoctor_callback');
            $redirect_uri = config('constants.QAdoctor_redirect_uri');
        } elseif ($userCalendarDetails->url == "/Staggingpatient/outlook/callback") {
            $callback = config('constants.Staggingpatient_callback');
            $redirect_uri = config('constants.Staggingpatient_redirect_uri');
        } elseif ($userCalendarDetails->url == "/Staggingdoctor/outlook/callback") {
            $callback = config('constants.Staggingdoctor_callback');
            $redirect_uri = config('constants.Staggingdoctor_redirect_uri');
        } elseif ($userCalendarDetails->url == "/Productionpatient/outlook/callback") {
            $callback = config('constants.Productionpatient_callback');
            $redirect_uri = config('constants.Productionpatient_redirect_uri');
        } elseif ($userCalendarDetails->url == "/Productiondoctor/outlook/callback") {
            $callback = config('constants.Productiondoctor_callback');
            $redirect_uri = config('constants.Productiondoctor_redirect_uri');
        }



        $scopes = ["User.Read offline_access"];
        $host = "https://login.microsoftonline.com/";

        $code = request()->code;
        $url = $host . $tenant_id . "/oauth2/v2.0/token";
        $request = new \GuzzleHttp\Client();

        $response = $request->post($url, [
            'form_params' => [
                'client_id'         => $client_id,
                'client_secret'     => $client_secret,
                'redirect_uri'      => $redirect_uri,
                'scope' => implode(' ', $scopes),
                'grant_type'        => 'authorization_code',
                'code'              => request()->code,

            ],
        ])->getBody()->getContents();
        $responseData = json_decode($response);

        session()->put('accessToken', $responseData->access_token);

        $url = 'https://graph.microsoft.com/v1.0/me';
        $token = session()->get('accessToken');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ])->get($url);

        $jsonResponse = json_decode($response);
        $loginEmail = CalendarSyncUser::latest()->first();
        $loginEmail->calendar_email = $jsonResponse->mail;
        $loginEmail->status = true;
        $loginEmail->update();

        UserOutlookCalendar::where('url', $userCalendarDetails->url)->update([
            'access_token' => $responseData->access_token,
            'expires_in' => $responseData->expires_in,
            'scope' => $responseData->scope,
            'token_type' => $responseData->token_type,
            'refresh_token' => isset($responseData->refresh_token) ? $responseData->refresh_token : null,
            'id_token' => isset($responseData->id_token) ? $responseData->id_token : null,
            'token_updated_at' =>  \Carbon\Carbon::now(),
            'token_updated_by_patient' =>  \Carbon\Carbon::now(),
            'user_email' => $jsonResponse->mail,
            // 'login_email' =>   "qasimbhatto@gmail.com",
            'login_email' =>   $loginEmail->login_email,

        ]);


        if ($userCalendarDetails->url == "/doctor/outlook/callback") {
            return redirect()->away(config('constants.GOOGLE_DOCTOR_LOGIN'));
        } elseif ($userCalendarDetails->url == "/patient/outlook/callback") {
            return redirect()->away(config('constants.GOOGLE_PATIENT_LOGIN'));
        } elseif ($userCalendarDetails->url == "/QApatient/outlook/callback") {
            return redirect()->away(config('constants.QA_GOOGLE_PATIENT_LOGIN'));
        } elseif ($userCalendarDetails->url == "/QAdoctor/outlook/callback") {
            return redirect()->away(config('constants.QA_GOOGLE_DOCTOR_LOGIN'));
        } elseif ($userCalendarDetails->url == "/Staggingpatient/outlook/callback") {
            return redirect()->away(config('constants.STAGING_GOOGLE_PATIENT_LOGIN'));
        } elseif ($userCalendarDetails->url == "/Staggingdoctor/outlook/callback") {

            return redirect()->away(config('constants.STAGING_GOOGLE_DOCTOR_LOGIN'));
        } elseif ($userCalendarDetails->url == "/Productionpatient/outlook/callback") {

            return redirect()->away(config('constants.PRO_PATIENT_CALLBACK'));
        } elseif ($userCalendarDetails->url == "/Productiondoctor/outlook/callback") {

            return redirect()->away(config('constants.PRO_DOCTOR_CALLBACK'));
        }
    }

    public function event($request)
    {
        $patient = Patient::where('id', $request['patient_id'])->first();
        $doctor = Doctor::where('id', $request['doctor_id'])->first();
        $patientCalendar = UserOutlookCalendar::where('login_email', $patient['email'])->first();
        if ($patientCalendar) {
            self::sendEventToPatient($patientCalendar, $request, $doctor);
        }
        $doctorCalendar = UserOutlookCalendar::where('login_email', $doctor['primary_email'])->first();
        if ($doctorCalendar) {
            self::sendEventToDoctor($doctorCalendar, $request, $patient);
        }
    }

    public static function  sendEventToPatient($patientCalendar, $request, $doctor)
    {

        $startDateTime = Carbon::parse($request['date'] . ' ' . $request['start_time'])->format('c');
        $endDateTIme = Carbon::parse($request['date'] . ' ' . $request['end_time'])->format('c');
        $token = $patientCalendar->access_token;


        if ($patientCalendar->token_updated_at  <= Carbon::now()->subHour()) {
            $refreshToken = $patientCalendar->refresh_token;

            $tenant_id = config('constants.patient_tenant_id');
            $host = "https://login.microsoftonline.com/";

            $scopes = ["User.Read offline_access"];

            $refreshToken = $patientCalendar->refresh_token;
            $endPoint      = $host . $tenant_id . "/oauth2/v2.0/token";
            $request = new \GuzzleHttp\Client();
            $response = $request->post($endPoint, [
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded"
                ],
                'form_params' => [
                    'client_id' => config('constants.patient_client_id'),
                    'client_secret' => config('constants.patient_client_secret'),
                    'grant_type'    => 'refresh_token',
                    'scope' => implode(' ', $scopes),
                    'refresh_token' =>  $refreshToken,
                ],
            ])->getBody()->getContents();
            $responseDataPatient = json_decode($response);
            if ($responseDataPatient) {
                UserOutlookCalendar::where('id', $patientCalendar['id'])->update([
                    'access_token' => $responseDataPatient->access_token,
                    'expires_in' => $responseDataPatient->expires_in,
                    'scope' => $responseDataPatient->scope,
                    'token_type' =>  $responseDataPatient->token_type,
                    'id_token' =>  isset($responseDataPatient->id_token,) ? $responseDataPatient->id_token : null,
                    'refresh_token' =>  $responseDataPatient->refresh_token,
                    'token_updated_at' => \Carbon\Carbon::now(),
                    'token_updated_by_patient' =>  \Carbon\Carbon::now(),
                    'url' => $patientCalendar['url'],

                ]);
            }
            $endPoint = "https://graph.microsoft.com/v1.0/me/events";
            $requestData = [
                'subject' => 'Appointment confirmed',
                'body' => [
                    'contentType' => 'HTML',
                    'content' => "Your appointment has been confirmed with" . ' ' . $doctor['first_name'] . ' ' . $doctor['middle_name'] . ' ' . $doctor['last_name'],
                ],
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => 'Asia/Karachi',
                ],
                'end' => [
                    'dateTime' =>  $endDateTIme,
                    'timeZone' => 'Asia/Karachi',
                ],
                // 'location' => [
                //     'displayName' => 'Cordova conference room',
                // ],
                'attendees' => [
                    0 => [
                        'emailAddress' => [
                            'address' => $patientCalendar->user_email,
                            'name' => 'Adele Vance',
                        ],
                        'type' => 'required',
                    ],

                ],
                'allowNewTimeProposals' => true,
                'isOnlineMeeting' => true,
                'onlineMeetingProvider' => 'teamsForBusiness',
            ];
            $request = new \GuzzleHttp\Client();
            $response = $request->post($endPoint, [
                "headers" => [
                    "Authorization" => "Bearer " . $token
                ],
                'json' => $requestData
            ])->getBody()->getContents();
        } else {

            $endPoint = "https://graph.microsoft.com/v1.0/me/events";
            $requestData = [
                'subject' => 'Appointment confirmed',
                'body' => [
                    'contentType' => 'HTML',
                    'content' => "Your appointment has been confirmed with" . ' ' . $doctor['first_name'] . ' ' . $doctor['middle_name'] . ' ' . $doctor['last_name'],
                ],
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => 'Asia/Karachi',
                ],
                'end' => [
                    'dateTime' =>  $endDateTIme,
                    'timeZone' => 'Asia/Karachi',
                ],
                // 'location' => [
                //     'displayName' => 'Cordova conference room',
                // ],
                'attendees' => [
                    0 => [
                        'emailAddress' => [
                            'address' => $patientCalendar->user_email,
                            'name' => 'Adele Vance',
                        ],
                        'type' => 'required',
                    ],

                ],
                'allowNewTimeProposals' => true,
                'isOnlineMeeting' => true,
                'onlineMeetingProvider' => 'teamsForBusiness',
            ];
            $request = new \GuzzleHttp\Client();
            $response = $request->post($endPoint, [
                "headers" => [
                    "Authorization" => "Bearer " . $token
                ],
                'json' => $requestData
            ])->getBody()->getContents();
        }
    }

    public static function  sendEventToDoctor($doctorCalendar, $request, $patient)
    {

        $startDateTime = Carbon::parse($request['date'] . ' ' . $request['start_time'])->format('c');
        $endDateTIme = Carbon::parse($request['date'] . ' ' . $request['end_time'])->format('c');
        $token = $doctorCalendar->access_token;
        if ($doctorCalendar->token_updated_at  <= Carbon::now()->subHour()) {
            $refreshToken = $doctorCalendar->refresh_token;

            $tenant_id = config('constants.patient_tenant_id');
            $host = "https://login.microsoftonline.com/";

            $scopes = ["User.Read offline_access"];

            $refreshToken = $doctorCalendar->refresh_token;
            $endPoint      = $host . $tenant_id . "/oauth2/v2.0/token";
            $request = new \GuzzleHttp\Client();
            $response = $request->post($endPoint, [
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded"
                ],
                'form_params' => [
                    'client_id' => config('constants.patient_client_id'),
                    'client_secret' => config('constants.patient_client_secret'),
                    'grant_type'    => 'refresh_token',
                    'scope' => implode(' ', $scopes),
                    'refresh_token' =>  $refreshToken,
                ],
            ])->getBody()->getContents();
            $responseDataDoctor = json_decode($response);
            if ($responseDataDoctor) {
                UserOutlookCalendar::where('id', $doctorCalendar['id'])->update([
                    'access_token' => $responseDataDoctor->access_token,
                    'expires_in' => $responseDataDoctor->expires_in,
                    'scope' => $responseDataDoctor->scope,
                    'token_type' =>  $responseDataDoctor->token_type,
                    'id_token' =>  isset($responseDataDoctor->id_token,) ? $responseDataDoctor->id_token : null,
                    'refresh_token' =>  $responseDataDoctor->refresh_token,
                    'token_updated_at' => \Carbon\Carbon::now(),
                    'token_updated_by_patient' =>  \Carbon\Carbon::now(),
                    'url' => $doctorCalendar['url'],

                ]);
            }
            $endPoint = "https://graph.microsoft.com/v1.0/me/events";
            $requestData = [
                'subject' => 'Appointment confirmed',
                'body' => [
                    'contentType' => 'HTML',
                    'content' => "Your appointment has been confirmed with" . ' ' . $patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name']
                ],
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => 'Asia/Karachi',
                ],
                'end' => [
                    'dateTime' =>  $endDateTIme,
                    'timeZone' => 'Asia/Karachi',
                ],
                // 'location' => [
                //     'displayName' => 'Cordova conference room',
                // ],
                'attendees' => [
                    0 => [
                        'emailAddress' => [
                            'address' => $doctorCalendar->user_email,
                            'name' => 'Adele Vance',
                        ],
                        'type' => 'required',
                    ],

                ],
                'allowNewTimeProposals' => true,
                'isOnlineMeeting' => true,
                'onlineMeetingProvider' => 'teamsForBusiness',
            ];

            $request = new \GuzzleHttp\Client();

            $response = $request->post($endPoint, [
                "headers" => [
                    "Authorization" => "Bearer " . $token
                ],
                'json' => $requestData
            ])->getBody()->getContents();
        } else {
            $endPoint = "https://graph.microsoft.com/v1.0/me/events";
            $requestData = [
                'subject' => 'Appointment confirmed',
                'body' => [
                    'contentType' => 'HTML',
                    'content' => "Your appointment has been confirmed with" . ' ' . $patient['first_name'] . ' ' . $patient['middle_name'] . ' ' . $patient['last_name']
                ],
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => 'Asia/Karachi',
                ],
                'end' => [
                    'dateTime' =>  $endDateTIme,
                    'timeZone' => 'Asia/Karachi',
                ],
                // 'location' => [
                //     'displayName' => 'Cordova conference room',
                // ],
                'attendees' => [
                    0 => [
                        'emailAddress' => [
                            'address' => $doctorCalendar->user_email,
                            'name' => 'Adele Vance',
                        ],
                        'type' => 'required',
                    ],

                ],
                'allowNewTimeProposals' => true,
                'isOnlineMeeting' => true,
                'onlineMeetingProvider' => 'teamsForBusiness',
            ];

            $request = new \GuzzleHttp\Client();

            $response = $request->post($endPoint, [
                "headers" => [
                    "Authorization" => "Bearer " . $token
                ],
                'json' => $requestData
            ])->getBody()->getContents();
        }
    }

    public static function getUserEmail(Request $request)
    {
        $loginEmail = $request->email;
        CalendarSyncUser::where(['login_email' => $loginEmail, 'sync_type' => 'outlook', 'status' => 0])->delete();
        CalendarSyncUser::create([

            'login_email' =>   $loginEmail,
            'sync_type' => "outlook",

        ]);
    }


    public static function getPatientEmail(Request $request)
    {
        $loginEmail = $request->email;
        CalendarSyncUser::where(['login_email' => $loginEmail, 'sync_type' => 'outlook', 'status' => 0])->delete();
        CalendarSyncUser::create([

            'login_email' =>   $loginEmail,
            'sync_type' => "outlook",

        ]);
    }


    public function removeOutlookCalendar(Request $request)
    {

        $loginUser = $request->email;
        UserOutlookCalendar::where('login_email',  $loginUser)->delete();
        CalendarSyncUser::where(['login_email' =>  $loginUser, "sync_type" => "outlook", "status" => true])->delete();
    }
}

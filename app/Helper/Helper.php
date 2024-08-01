<?php

namespace App\Helper;

use App\Models\ActivityLog\ActivityLog;
use App\Models\CredentialLog\CredentialLog;
use App\Models\Doctor\DoctorPractice;
use App\Models\ErrorLog\ErrorLog;
use App\Models\OtpVerification\OtpVerification;
use App\Models\ZoomAppointmentDetail;
use App\Models\ZoomCredentials;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use JetBrains\PhpStorm\NoReturn;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Twilio\Http\GuzzleClient;

class Helper
{
    public static function serverErrorLog($function_name, $exception, $line_number): void
    {
        $error_log = new ErrorLog();
        $error_log->method = $function_name;
        $error_log->error_message = $exception;
        $error_log->line_number = $line_number;
        $error_log->save();
    }

    public static function exceptionLogs($function_name, $exception, $message, $status): array
    {
        $error = 'Message: ' . $message . ' File Name: ' . $exception->getFile() . ' Line number: ' . $exception->getLine() . ' Status Code:' . $exception->getCode();
        self::serverErrorLog($function_name, $error, $exception->getLine());
        return [
            "http_status" => $status,
        ];
    }

    // Save activity_logs which required message(performed activity), request_body and response
    public static function activityLog($message, $rawRequest = null, $rawResponse = null)
    {
        $log['type'] = Auth::getDefaultDriver();
        $log['user_id'] = Auth::id();
        $log['message'] = $message;
        $log['endpoint'] = request()->fullUrl();
        $log['method'] = request()->method();
        $log['ip'] = request()->ip();
        $log['user_agent'] = request()->header('user-agent');
        $log['raw_request'] = $rawRequest;
        $log['raw_response'] = $rawResponse;
        activityLog::create($log);
    }

    // Generate OTP for verification
    public static function otpGenerator(): string
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
    }

    public static function credentialLog($userID, $guardName, $type, $value): void
    {
        CredentialLog::create([
            'user_id' => $userID,
            'guard_name' => $guardName,
            'type' => $type,
            'value' => $value
        ]);
    }

    public static function otpVerification($userID, $guardName,$otp, $type, $value): void
    {
        OtpVerification::where(['user_id' => $userID, 'guard_name' => Auth::getDefaultDriver()])->where('type', $type)->delete();
        OtpVerification::create([
            'user_id' => $userID,
            'guard_name' => $guardName,
            'otp' => $otp,
            'value' => $value,
            'type' => $type,
        ]);
    }

    /**
     * This function will trigger Notification for developer about the Bug.
     *
     * @param [type] $error
     * @return void
     */
    public static function triggerNotification($error)
    {
        $url =false;
        $url = env('LOG_SLACK_WEBHOOK_URL');
        $route = Route::getCurrentRoute() ? Route::getCurrentRoute()->getActionName() : 'Route Not Found';
        Http::withHeaders([
            'Content-type' => 'application/json'
        ])->post($url, [
            "attachments" => [[
                "mrkdwn_in" => ["text"],
                "color" => "#36a64f",
                "title" => "Exception Error",
                'text' =>
                '*Date:* ' .
                    date('Y-m-d', strtotime('TODAY')) .
                    "\n" .
                    "*Error Message:* " . $error->getMessage() .
                    "\n" .
                    "*Function Name:* " .$route.
                    "\n" .
                    "*Line Number:* " . $error->getLine() .
                    "\n" .
                    "*File Name:* " . $error->getFile(),
            ]]
        ]);
    }

    public static function doctor_id($request)
    {
        if ($request->has('doctor_id'))
        {
            $doctor_id = $request->doctor_id;
        }
        else
        {
            $doctor_id = auth()->guard('doctor-api')->id();
        }
        return $doctor_id;
    }

    public static function webNotification(): void
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = [
            'crO3XBbO8XSIWAFq2_yTTA:APA91bGWwwEEp5Z_ixyuvRjlo_rNRjwJRaSU6wIAVeH6rIufZsn2rNi4LbqjqBzNAvuFIRYDvV6xSCfxx5MxTmga8S8AaFAkYT1qB8wcs0kzOQdYPvK3sUObZX6aPtpvsKzK2Djb0Pct'
        ];

        $serverKey = 'AAAA3jTuLmI:APA91bGz9plxQ7zDvBAGpZwKDu93J85GNlpTyHw2Y9nHVTX_E0a1kIwKIIDzKpOTpHdn33WbMJBym5zjh0vWIecBY8M8zcBmwIJTzXBF3oJHRczhlrmotEl2-0NqM3S2sfdJjzgTQMVZ';

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => 'Title for test notification.',
                "body" => 'Request to send notification.',
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }

    public static function makeHttpGetRequest($endPoint, $accessToken, $formParams = [], $requestBody = [])
    {
        $request = new Client();
        $response = $request->get($endPoint, [
            "headers" => [
                "Authorization" => "Bearer " . $accessToken
            ],
            'json' => $requestBody
        ]);
        return $response;
    }

    public static function makeHttpPostRequest($endPoint, $accessToken, $formParams = [], $requestBody = [])
    {
        try {
            $request = new Client();
            $response = $request->post($endPoint, [
                "headers" => [
                    'Authorization' => 'Bearer ' . $accessToken
                ],
                'json' => $requestBody
            ]);
            return $response;
        } catch (RequestException $e) {
            return response()->json([
                'error' => $e->getResponse()->getReasonPhrase(),
                'status_code' => $e->getCode(),
            ], $e->getCode());
        }
        
    }
    
    public static function refreshToken()
    {
        try {
            $zoom  = ZoomCredentials::where('id', 1)->first();
            $clientID       = $zoom->client_id;
            $clientSecret   = $zoom->client_secret;
            $refreshToken = $zoom->refresh_token;
            $endPoint            = 'https://zoom.us/oauth/token';

            $request = new Client();
            $response = $request->post($endPoint, [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode($clientID . ':' . $clientSecret),
                ],
                'form_params' => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refreshToken
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody()->getContents());
                ZoomCredentials::where('id', 1)->update([
                    'access_token' => $responseData->access_token,
                    'refresh_token' => $responseData->refresh_token,
                    'token_updated_at' => now()
                ]);
                return $responseData->access_token;
            }
        } catch (RequestException $e) {
            return response()->json([
                'error' => $e->getResponse()->getReasonPhrase(),
                'status_code' => $e->getCode(),
            ], $e->getCode());
        }
    }

    public static function bookZoomAppointment($request, $patient, $doctor, $appointment)
    {
        $zoom  = ZoomCredentials::where('id', 1)->first();
        $userID = $doctor->zoom_user_id;
        $accessToken = $zoom->access_token;

        if ($zoom->token_updated_at <= Carbon::now()->subHour()) {
            // Token has expired (1 hour)
            $accessToken = Helper::refreshToken();
        }

        // Meeting Duration
        // $startTime = strtotime($request->start_time);
        // $endTime = strtotime($request->end_time);
        // $duration = round(($endTime - $startTime) / 60);
        $doctorName = $doctor->first_name . ' ' . $doctor->middle_name . ' ' . $doctor->last_name;
        $startDateTime = Carbon::parse($request['date']. ' ' . $request['start_time'])->format('c');

        $endPoint = 'https://api.zoom.us/v2/users/' . $userID . '/meetings';
        $requestBody = [
            "topic"             => "Video Consultancy Service with " . $doctorName,
            "type"              => 2,
            "timezone"          => "Asia/Tashkent",
            "use_pmi"          => true,
            'start_time'        => $startDateTime,
            "duration"          => '30',
            "password"          => "12345678",
            "meeting_authentication" => false,
            // "recurrence" => [
            //     "schedule_for" => 'naveed.akram@developers.studio',
            // ],
            "settings" => [
                "join_before_host"          => true,
                "email_notification	"       => true,
                'allow_multiple_devices'    => true,
                // 'alternative_hosts'      => 'naveed.akram@developers.studio;abdullah@developers.studio',
                // 'alternative_hosts_email_notification' => true,
                'meeting_invitees' => [
                    0 => [
                        'email' => $patient->email,
                    ],
                ],
            ]
        ];

        $response = Helper::makeHttpPostRequest($endPoint, $accessToken, [], $requestBody);
        if ($response->getStatusCode() == 201) {
            $responseData = json_decode($response->getBody()->getContents());
            $zoomCredentials = [
                'appointment_id' => $appointment->id,
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'join_url'  => $responseData->join_url,
                'start_url'   => $responseData->start_url,
            ];

            ZoomAppointmentDetail::create($zoomCredentials);
            $data['status_code'] = $response->getStatusCode();
            $data['response_data'] = $responseData;
            return $data;
        }
        $data['status_code'] = $response->getStatusCode();
        return $data;
    }

}

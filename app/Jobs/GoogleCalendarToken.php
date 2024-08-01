<?php

namespace App\Jobs;

use App\Helper\Helper;
use App\Models\UserCalendar;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use GuzzleHttp\Client;

class GoogleCalendarToken implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->onQueue(config('constants.GOOGLE_CALENDER_TOKEN'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
           $usersCalendar = UserCalendar::get();

        foreach ($usersCalendar as $userCalendars) {

            if ( $userCalendars ['url'] == "/patient/google/callback") {
                $clientID       = '960382014976-bus6831fev2ibq7njke573nmmrtjtj0k.apps.googleusercontent.com';
                $clientSecret   = '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';
            } elseif ($userCalendars ['url'] == "/QApatient/google/callback") {
                $clientID       = '274329508758-c070fmjvl6n4ndmiqustre3jqih4b27o.apps.googleusercontent.com';
                $clientSecret   = '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';
            }
            elseif ($userCalendars ['url'] == "/Staggingpatient/google/callback") {
                $clientID       = '1033462572280-srtol1tiu4r9kjies0c661os3hj8sjk2.apps.googleusercontent.com';
                $clientSecret   = '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';
            }
            elseif ($userCalendars ['url'] == "/google/callback") {
                $clientID       = '183129290208-9maht5rd37bie8p72v87e7th7q3h28i2.apps.googleusercontent.com';
                $clientSecret   = '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';
            } elseif ($userCalendars ['url'] == "/QAdoctor/google/callback") {
                $clientID       = '843052042406-9b6hqli2qss97iqal2177s1jnasv4iii.apps.googleusercontent.com';
                $clientSecret   = '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';
            }
            elseif ($userCalendars ['url'] == "/Staggingdoctor/google/callback") {
                $clientID       = '892586939996-9kk9tm44su6nqbpm74f1nbbic3sos25g.apps.googleusercontent.com';
                $clientSecret   = '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';
            }
            elseif ($userCalendars ['url'] == "/production-doctor/google/callback") {
                $clientID       = '17924911703-mirmnviceg6nr20jnf8tv00evojhhg53.apps.googleusercontent.com';
                $clientSecret   = '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';
            }
            elseif ($userCalendars ['url'] == "/production-patient/google/callback") {
                $clientID       = '380765635739-k252uo1iili2gr60lsvp5h304813mmsc.apps.googleusercontent.com';
                $clientSecret   = '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';
            }

            $refresh_token  =  $userCalendars ['refresh_token'];
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
                    'client_secret' => $clientSecret,
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refresh_token,
                ],
            ]);
            $responseDataPatient = (json_decode($responsePatient->getBody()->getContents()));
            if ($responseDataPatient) {

                $userCalendars->access_token = $responseDataPatient->access_token;
                $userCalendars->expires_in = $responseDataPatient->expires_in;
                $userCalendars->scope = $responseDataPatient->scope;
                $userCalendars->token_type =  $responseDataPatient->token_type;
                $userCalendars->id_token = isset($responseDataPatient->id_token) ? $responseDataPatient->id_token : null;
                $userCalendars->refresh_token = $refresh_token;
                $userCalendars->token_updated_at= \Carbon\Carbon::now();

                $userCalendars->token_updated_by_patient =  \Carbon\Carbon::now();

                $userCalendars->update();

            }

        }
        } catch (Exception $exception) {
            return $this->exception($exception);
        }
    }

    public function failed(\Exception $exception)
    {
        Helper::triggerNotification($exception);
    }
}

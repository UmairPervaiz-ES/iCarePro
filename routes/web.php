<?php

use App\Http\Controllers\EPrescription\EPrescriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\gCalendarController;
use App\Http\Controllers\oCalendarController;
use Twilio\Rest\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/' , function(){
    return "API is up and running";
});

// Route::get('/eprescription/view' , [EPrescriptionController::class , 'viewEp']);

Route::get('test' , function(){
    // $account_sid = env('TWILIO_SID');
    // $account_token = env('TWILIO_TOKEN');
    // $account_from = env('TWILIO_FROM');
    // $phone_number= "+923156263580";
    // $client = new Client($account_sid, $account_token);
    // $client->messages->create(
    //     $phone_number,
    //     [
    //         'from' => $account_from,
    //         'body' => 'ICarePro Mobile Phone Verification Code 123456'
    //     ]
    // );
    // $token = env("TWILIO_TOKEN");
    // $twilio_sid = env("TWILIO_SID");
    // $twilio_verify_sid = env("TWILIO_VERIFY_SID");
    // $twilio = new Client($twilio_sid, $token);
    // $twilio->verify->v2->services($twilio_verify_sid)
    //     ->verifications
    //     ->create('+13134048290', "sms");
    // dd($twilio);
    // return redirect()->away("https://google.com");

    // return view('test');
    // echo "<script>window.close();</script>";
    // exit();
});

Route::get('download-pdf/{patient}/{pdf}', function($patient,$pdf){
    $file=  public_path() .'/storage/e-prescriptions/'. $patient . '/' . $pdf . '.pdf';
    $headers = array('Content-Type: application/pdf',);
    return Response::download($file, $pdf . '.pdf', $headers);
});


/*
|--------------------------------------------------------------------------
| google Routes
|-------------------------------------------------------------------------
|
*/


Route::resource('cal', gCalendarController::class);
// Dev route
Route::get('/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');
Route::get('/patient/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');

// QA route
Route::get('/QApatient/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');
Route::get('/QAdoctor/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');

// Stagging route
Route::get('/Staggingpatient/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');
Route::get('/Staggingdoctor/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');


// Production route (tem local)
Route::get('/production-doctor/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');
Route::get('/production-patient/google/callback', [gCalendarController::class, 'oauth'])->name('oauthCallback');


/*
|--------------------------------------------------------------------------
| Microsoft Routes
|-------------------------------------------------------------------------
|
*/
Route::get('/patient/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/patient/oauth/callback', [oCalendarController::class, 'callBack']);
Route::get('/event', [oCalendarController::class, 'event']);

Route::get('/doctor/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/doctor/oauth/callback', [oCalendarController::class, 'callBack']);

// QA route
Route::get('/QAdoctor/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/QAdoctor/oauth/callback', [oCalendarController::class, 'callBack']);

Route::get('/QApatient/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/QApatient/oauth/callback', [oCalendarController::class, 'callBack']);
// Staging route

Route::get('/Staggingpatient/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/Staggingpatient/oauth/callback', [oCalendarController::class, 'callBack']);

Route::get('/Staggingdoctor/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/Staggingdoctor/oauth/callback', [oCalendarController::class, 'callBack']);

// production route (tem local)

Route::get('/Productionpatient/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/Productionpatient/oauth/callback', [oCalendarController::class, 'callBack']);

Route::get('/Productiondoctor/outlook/callback', [oCalendarController::class, 'getAuthUrl']);
Route::get('/Productiondoctor/oauth/callback', [oCalendarController::class, 'callBack']);

/*
|--------------------------------------------------------------------------
| Zoom Auth Routes
|-------------------------------------------------------------------------
|
*/
Route::get('zoom/login', [ZoomController::class, 'login']);
Route::get('zoom/callback', [ZoomController::class, 'callback']);



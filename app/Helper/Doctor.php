<?php

namespace App\Helper;

use App\Traits\RespondsWithHttpStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Doctor
{
    use RespondsWithHttpStatus;

    /**
     *  Description: Transaction function to store doctor
     *  1) Requested doctor details are passed to this function
     *  2) All details related to doctors are saved
     *  3) Requested images are in base64 format
     *  4) Returns doctor model instance
     *
     * @param $doctorDetails
     * @param $doctorAddressDetails
     * @param $doctorRequest
     * @param $doctorLegalInformation
     * @param $practice_id
     * @return mixed
     */
    public function transaction($doctorDetails, $doctorAddressDetails, $doctorRequest, $doctorLegalInformation, $practice_id): mixed
    {
        return DB::transaction(function () use ($doctorDetails, $doctorAddressDetails, $doctorRequest, $doctorLegalInformation, $practice_id)
        {
            // Checking whether incoming request is a new request to create new doctor or update previous doctor added as draft
            if ($doctorRequest->has('doctor_id')){
                $doctor = \App\Models\Doctor\Doctor::where('id', $doctorRequest->doctor_id)->first();
                $doctor->update($doctorDetails);
            }
            else{
                $doctor = \App\Models\Doctor\Doctor::create($doctorDetails);
                $doctor->update(['doctor_key' => 'doctor-' . $doctor->id]);      // Updating doctor_key after doctor is created because for now it saves doctor ID with static string.
            }

            // Saving doctor's profile_photo_url
            if ($doctorRequest->profile_photo_url){
                $this->profile_photo_url($doctorRequest, $doctorRequest->profile_photo_url, $practice_id, $doctor);
            }

            // Saving doctor's license_photo_url
            $this->license_photo_url($doctorRequest, $doctorRequest->license_photo_url, $practice_id, $doctor);

            // Saving doctor's passport_photo_url
            $this->passport_photo_url($doctorRequest, $doctorRequest->passport_photo_url, $practice_id, $doctor);

            // Saving doctor's emirate_photo_url
            $this->emirate_photo_url($doctorRequest, $doctorRequest->emirate_photo_url, $practice_id, $doctor);

            $doctor->doctorAddress()->updateOrCreate($doctorAddressDetails);

            // Saving doctors' specializations
            $this->doctorSpecializations($doctor, $doctorRequest);

            // COMMENTED FOR NOW
            // Saving doctors' uploaded documents
            /*foreach ($doctorRequest->file('file_path') as $doctorDocument)
            {
                $filenameWithExt = $doctorDocument->getClientOriginalName();
                //Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $doctorDocument->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                // Upload Image
                $path = $doctorDocument->storeAs('public/practice/' . auth()->guard('practice-api')->id() . '/' . 'doctor/' . $doctor->id . '/documents', $fileNameToStore);

                $doctor->doctorDocuments()->create([
                    'file_path' => $path,
                    'created_by' => auth()->guard('practice-api')->user()->practice_key,
                ]);
            }*/

            // Saving doctors' legal information
            $doctor->doctorLegalInformation()->updateOrCreate($doctorLegalInformation);

            return $doctor;
        });
    }

    /**
     *  Description: Local function used by transaction function
     *
     * @param $doctorRequest
     * @param $img
     * @param $practice_id
     * @param $doctor
     * @return void
     */
    public function profile_photo_url($doctorRequest, $img, $practice_id, $doctor): void
    {
        ################## Saving doctor's profile_photo_url and if present deleting the previous photo inorder to prevent duplication ##################
        if (str_contains($doctorRequest->profile_photo_url, 'data:'))
        {
            if (Storage::exists($img)){
                Storage::delete('/'.$img);
            }

            $folderPath = 'public/practice/'. $practice_id . '/' .'doctor/'.$doctor->id . '/profile_photos/' ; //path location
            $extension = explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($img, 0, strpos($img, ',')+1);
            // find substring from replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $img);
            $image = str_replace(' ', '+', $image);
            $imageName = $folderPath . Str::random(15).'.'.$extension;

            Storage::disk('local')->put($imageName, base64_decode($image));
            $doctor->update(['profile_photo_url' => $imageName]);
        }
    }

    /**
     *  Description: Local function used by transaction function
     *
     * @param $doctorRequest
     * @param $img
     * @param $practice_id
     * @param $doctor
     * @return void
     */
    public function license_photo_url($doctorRequest, $img, $practice_id, $doctor): void
    {
        // Saving doctor's license_photo_url and if present deleting the previous photo inorder to prevent duplication
        if (str_contains($doctorRequest->license_photo_url, 'data:'))
        {
            if (Storage::exists($img)){
                Storage::delete('/'.$img);
            }

            $folderPath = 'public/practice/'. $practice_id . '/' .'doctor/'.$doctor->id . '/documents/' ; //path location
            $extension = explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($img, 0, strpos($img, ',')+1);
            // find substring from replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $img);
            $image = str_replace(' ', '+', $image);
            $imageName = $folderPath . Str::random(15).'.'.$extension;

            Storage::disk('local')->put($imageName, base64_decode($image));
            $doctor->update(['license_photo_url' => $imageName]);
        }
    }

    /**
     *  Description: Local function used by transaction function
     *
     * @param $doctorRequest
     * @param $img
     * @param $practice_id
     * @param $doctor
     * @return void
     */
    public function passport_photo_url($doctorRequest, $img, $practice_id, $doctor): void
    {
        ################## Saving doctor's passport_photo_url and if present deleting the previous photo inorder to prevent duplication ##################
        if (str_contains($doctorRequest->passport_photo_url, 'data:'))
        {
            if (Storage::exists($img)){
                Storage::delete('/'.$img);
            }

            $folderPath = 'public/practice/'. $practice_id . '/' .'doctor/'.$doctor->id . '/documents/' ; //path location
            $extension = explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($img, 0, strpos($img, ',')+1);
            // find substring from replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $img);
            $image = str_replace(' ', '+', $image);
            $imageName = $folderPath . Str::random(15).'.'.$extension;

            Storage::disk('local')->put($imageName, base64_decode($image));
            $doctor->update(['passport_photo_url' => $imageName]);
        }
    }

    /**
     *  Description: Local function used by transaction function
     *
     * @param $doctorRequest
     * @param $img
     * @param $practice_id
     * @param $doctor
     * @return void
     */
    public function emirate_photo_url($doctorRequest, $img, $practice_id, $doctor): void
    {
        ################## Saving doctor's emirate_photo_url and if present deleting the previous photo inorder to prevent duplication ##################
        if (str_contains($doctorRequest->emirate_photo_url, 'data:'))
        {
            if (Storage::exists($img)){
                Storage::delete('/'.$img);
            }

            $folderPath = 'public/practice/'. $practice_id . '/' .'doctor/'.$doctor->id . '/documents/' ; //path location
            $extension = explode('/', explode(':', substr($img, 0, strpos($img, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($img, 0, strpos($img, ',')+1);
            // find substring from replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $img);
            $image = str_replace(' ', '+', $image);
            $imageName = $folderPath . Str::random(15).'.'.$extension;

            Storage::disk('local')->put($imageName, base64_decode($image));
            $doctor->update(['emirate_photo_url' => $imageName]);
        }
    }

    /**
     *  Description: Local function used by transaction function
     *
     * @param $doctor
     * @param $requestSpecialization_id
     * @param $uniqueKey
     * @return void
     */
    public function doctorSpecializations($doctor, $doctorRequest): void
    {
        if ($doctor->doctorSpecializations()->exists()){
            $doctor->doctorSpecializations()->delete();
            foreach ($doctorRequest->specialization_id as $specializationID) {
                $doctor->doctorSpecializations()->create([
                    'specialization_id' => $specializationID,
                    'updated_by' => $this->uniqueKey(),
                ]);
            }
        }
        else{
            foreach ($doctorRequest->specialization_id as $specializationID) {
                $doctor->doctorSpecializations()->create([
                    'specialization_id' => $specializationID,
                    'created_by' => $this->uniqueKey(),
                ]);
            }
        }
    }

    /**
     *  Description: Function for doctor's KYC verification
     *  1) Doctor model is passed
     *  2) 3rd party shuftiPro API is called
     *  3) verification_url, reference_no generated by shuftiPro API is stored in doctors' table while saving doctor
     *  4) Returns verification_url, reference_no and response_data
     *
     * @param $doctor
     * @return array
     */
    public static function shuftiPro($doctor): array
    {
        $url = 'https://api.shuftipro.com/';

        //Your Shufti Pro account Client ID
        $client_id =  env('SHUFTIPRO_CLIENT_ID');
        //Your Shufti Pro account Secret Key
        $secret_key = env('SHUFTIPRO_SECRET_KEY');
        //OR Access Token
        $verification_request = [
            'reference'    => 'ref-' . rand(4, 444) . rand(4, 444),
            'country'      => 'AE',
            'language'     => 'EN',
            'email'        => $doctor->primary_email,
            'callback_url' =>  config('constants.PRACTICE_URL').'backend/api/doctor-kyc-shuftiPro-response',
            'redirect_url' => config('constants.PRACTICE_URL').'doctor/login',
            'show_feedback_form' => '0',
            'allow_retry' => '1',
            'verification_mode' => 'any',
            'ttl'          => '60',
        ];
        //Use this key if you want to perform face verification
        $verification_request['face'] = [
            'proof' => '',
            'allow_online'    => '1',
        ];
        //Use this key if you want to perform document verification
        $verification_request['document'] = [
            'name' => [
                'first_name' => '',
            ],
            'proof' => '',
            'additional_proof' => '',
            'dob'             => '',
            'allow_online'    => '1',
            'backside_proof_required' => '0',
            //'show_ocr_form' => 1,
            'supported_types' => ['id_card', 'passport'],
            'fetch_enhanced_data' => '1',
            "gender"   => ''
        ];

        $auth = $client_id . ":" . $secret_key; // remove this in case of Access Token
        $headers = ['Content-Type: application/json'];
        // if using Access Token then add it into headers as mentioned below otherwise remove access token
        // array_push($headers, 'Authorization : Bearer ' . $access_token);
        $post_data = json_encode($verification_request);
        //Calling Shufti Pro request API using curl
        $response = self::send_curl($url, $post_data, $headers, $auth); // remove $auth in case of Access Token
        //Get Shufti Pro API Response
        $response_data    = $response['body'];

        // Extracting verification code to mail it to doctor from a json encoded data response
        $decoded_response = json_decode($response_data, true);

        $verification_url = $decoded_response['verification_url'] ?? null;
        $reference_no = $decoded_response['reference'] ?? null;

        return [
            'response_data' => $response_data,
            'verification_url' => $verification_url,
            'reference_no' => $reference_no
        ];
    }

    /**
     *  Description: Local function used by shuftiPro function
     * @param $url
     * @param $post_data
     * @param $headers
     * @param $auth
     * @return array
     */
    static function send_curl($url, $post_data, $headers, $auth): array
    { // remove $auth in case of Access Token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $auth); // remove this in case of Access Token
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // remove this in case of Access Token
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $html_response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($html_response, 0, $header_size);
        $body = substr($html_response, $header_size);
        curl_close($ch);
        return ['headers' => $headers, 'body' => $body];
    }
}

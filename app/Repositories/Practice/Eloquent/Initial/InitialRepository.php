<?php

namespace App\Repositories\Practice\Eloquent\Initial;

use App\Jobs\Practice\InitialRequestSentAlertToUser;
use App\Jobs\Practice\PracticeRequestSentAlertToUser;
use App\Jobs\SuperAdmin\InitialRequestReceive;
use App\Jobs\SuperAdmin\PracticeRequestReceive;
use App\libs\Messages\PracticeGlobalMessageBook as PGMBook;
use App\Models\Practice\InitialPractice;
use App\Models\Practice\Practice;
use App\Models\Practice\PracticeAddress;
use App\Models\Practice\PracticeBillingAddress;
use App\Models\Practice\PracticeContact;
use App\Models\Practice\PracticeDocument;
use App\Models\Subscription\SubscriptionPermission;
use App\Models\SuperAdmin\SuperAdmin;
use App\Repositories\Practice\Interfaces\Initial\InitialRepositoryInterface;
use App\Traits\RespondsWithHttpStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Supersim;
use Illuminate\Support\Facades\Crypt;

class InitialRepository implements InitialRepositoryInterface
{
    use RespondsWithHttpStatus;

    /**
     *  Description:This function store initial practice
     *  1) This method is used to store initial practice
     *  2) If fields not validate ,field is required message will return
     *  3) In case of fields validate , initial practice store
     *  4) All field is required excepted middle name and email is unique
     *  5) Activity is logged, and a success message is return
     * @param mixed $request
     * @return Response
     */
    public function initialPractice($request): Response
    {
        $initialPractice = InitialPractice::create([
            'practice_name' => $request['practice_name'],
            'country_code' => $request['country_code'],
            'phone_number' => $request['phone_number'],
            'first_name' => $request['first_name'],
            'middle_name' => $request['middle_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'designation' => $request['designation'],
            'about_us' => $request['about_us'],
        ]);
        dispatch(new InitialRequestSentAlertToUser($initialPractice))->onQueue(config('constants.INITIAL_REQUEST_AlERT_SEND_TO_USET'));
        $superAdminData = SuperAdmin::first();
        $superAdmin = $superAdminData['email'];
        dispatch(new InitialRequestReceive($superAdmin,$initialPractice))->onQueue(config('constants.SUPER_ADMIN_INTIAL_REQUEST_RECEIVE'));

        return $this->response($request, $initialPractice, PGMBook::SUCCESS['INITIAL_REGISTER'], 201);
    }

    /**
     *  Description: This function store practice contact practice details , address and billing address
     *  1) This method is used to store initial practice further details
     *  2) If Initial id not exit message will return
     *  3) If fields not validate ,field is required message will return
     *  4) In case of fields are validated , it will store multi practice contact practice details , address and billing address
     *  5) All field is required excepted middle name and email is unique
     *  6) Use static function  practice, practiceAddress, practiceAddressBilling
     *  7) Activity is logged, and a success message is return
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function practiceRequest($request, $id)
    {
        $id = base64_decode($id);
        $practiceData = false;
        $data = $request->input();
        $initialPractice = InitialPractice::find($id);
        if (!$initialPractice) {
            $message = PGMBook::FAILED['PRACTICE_REQUEST_NOT_FOUND'];
            $status = 400;
            $success = false;
        } else if ($initialPractice) {
            $practiceEmailCheck = Practice::where('email', $initialPractice->email)->first();
            if ($practiceEmailCheck) {
                $message = PGMBook::FAILED['PRACTICE_EMAIL'];
                $status = 409;
                $success = false;
            } else {
                $emails = collect($data['contacts'])->map(function ($item, $key) {
                    return ($item['email']);
                });

                $checkUniqueness = PracticeContact::whereIn('email', $emails)->get();

                $emails = array_column($data['contacts'], 'email');
                $uniqueEmail = array_unique($emails);
                if (count($emails) == count($uniqueEmail)) {


                    foreach ($data['contacts'] as $contact) {

                        $practiceContact = PracticeContact::where('email', $contact['email'])->first();
                        if ($practiceContact) {
                            $unique = false;
                            $message = PGMBook::FAILED['EMAIL_EXISTS'];
                            $status = 409;
                            $success = false;
                        } elseif ($checkUniqueness->count() == 0) {
                            $unique = true;

                            $contactData = new PracticeContact;
                            $contactData->practice_registration_request_id = $id;
                            $contactData->country_code = $contact['country_code'];
                            $contactData->phone_number = $contact['phone_number'];
                            $contactData->first_name = $contact['first_name'];
                            $contactData->middle_name = $contact['middle_name'];
                            $contactData->last_name = $contact['last_name'];
                            $contactData->email = $contact['email'];
                            $contactData->designation = $contact['designation'];
                            $contactData->save();
                        }
                    }
                } else {
                    $message = PGMBook::FAILED['EMAIL_UNIQUE'];
                    $status = 409;
                    $success = false;
                }

                if ($checkUniqueness->count() == 0) {
                    $practice = static::practice($request, $id);
                    // practiceAddress add use static function
                    static::practiceAddress($request, $practice);
                    // practiceBillingAddress add use static function
                    static::practiceAddressBilling($request, $practice);

                    dispatch(new PracticeRequestSentAlertToUser($initialPractice))->onQueue(config('constants.INITIAL_REQUEST_AlERT_SEND_TO_USET'));
                    $superAdminData = SuperAdmin::first();
                    $superAdmin = $superAdminData['email'];
                    dispatch(new PracticeRequestReceive($superAdmin,$initialPractice))->onQueue(config('constants.SUPER_ADMIN_PRACTICE_REQUEST_RECEIVE'));
                    $message = PGMBook::SUCCESS['PRACTICE_REGISTER_REQUEST'];
                    $status = 201;
                    $success = true;
                    $practiceData = $practice;
                }

                // practice add use static function

            }
        }
        return $this->response($request, $practiceData, $message, $status, $success);
    }

    /**
     *  Description: This function Store practice document
     *  1) This method is used to store
     *  2) If Initial id not exit message will return
     *  4) Activity is logged, and a success message is return
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function practiceDocument($request, $id)
    {
        $data = $request->input();

        $filenameWithExt = $request->file('file_path')->getClientOriginalName();
        //Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('file_path')->getClientOriginalExtension();
        // Filename to store
        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
        // Upload Image
        $path = $request->file('file_path')->storeAs('public/practice/' . $id, $fileNameToStore);

        $documentData = new PracticeDocument;
        $documentData->practice_registration_request_id = $id;
        $documentData->name = isset($data['name']) ? $data['name'] : null;
        $documentData->file_path = $path;
        $documentData->save();

        $practiceDocument =  PracticeDocument::where("practice_registration_request_id", $id)->latest()->first();
        return $this->response($request, $practiceDocument, PGMBook::SUCCESS['PRACTICE_DOCUMENTS'], 201);
    }

    /**
     *  Description:This function Delete practice document
     *  1) This method is used to delete
     *  2) If id is not exist message will return
     *  3) In case of id exist, document deleted
     *  4) Activity is logged, and a success message is return
     * @param  mixed $id
     * @return void
     */

    public function practiceDocumentDelete($id)
    {
        $practiceDocument = PracticeDocument::find($id);
        if ($practiceDocument) {
            $document = $practiceDocument->delete();

            $message = PGMBook::SUCCESS['DOCUMENT_DELETE'];
            $status = 200;
            $success = true;
        } else {
            $document = false;
            $message = PGMBook::FAILED['DOCUMENT_NOT_FOUND'];
            $status = 400;
            $success = false;
        }
        return $this->response(true, $document, $message, $status, $success);
    }

    /**
     *  Description: This function store practice contact
     *  1) This method is used to store
     *  2) If Initial id not exit message will return
     *  3) If fields not validate ,field is required message will return
     *  4) In case of fields validate , store practice
     *  5) All field is required ,email is unique
     *  6) Activity is logged, and a success message is return
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public static function practice($request, $id)
    {
        $initialPractice = InitialPractice::find($id);
        $practice = new Practice;
        $practice->practice_registration_request_id = $id; //practice registration request  id
        $practice->subscription_id = 1;
        $practice->practice_key =  Str::random(19);
        $practice->logo_url = $request['logo_url'];
        $practice->email = $initialPractice->email;
        $practice->password = null;
        $practice->tax_id = $request['tax_id'];
        $practice->practice_npi = $request['practice_npi'];
        $practice->practice_taxonomy = $request['practice_taxonomy'];
        $practice->facility_id = $request['facility_id'];
        $practice->oid = $request['oid'];
        $practice->clia_number = $request['clia_number'];
        $practice->privacy_policy = $request['privacy_policy'];
        $practice->save();
        $practice->update(['practice_key' => 'practice-'.$practice->id]);
        $practice->save();
        return $practice;
    }

    /**
     *  Description: This function store practice address
     *  1) This method is used to store
     *  2) If Initial id not exit message will return
     *  3) If fields not validate ,field is required message will return
     *  4) In case of fields validate ,practice address
     *  5) All field is required
     *  6) Activity is logged, and a success message is return
     * @param  mixed $request
     * @param  mixed $practice
     * @return void
     */
    public static function practiceAddress($request, $practice)
    {
        $practiceAddress = new PracticeAddress;
        $practiceAddress->practice_id = $practice['id'];  //practice id
        $practiceAddress->address_line_1 = $request['address_line_1'];
        $practiceAddress->address_line_2 = $request['address_line_2'];
        $practiceAddress->country_id = $request['country_id'];
        $practiceAddress->city_id = $request['city_id'];
        $practiceAddress->state_id = $request['state_id'];
        $practiceAddress->zip_code = $request['zip_code'];
        $practiceAddress->lat = $request['lat'];
        $practiceAddress->lng = $request['lng'];
        $practice->practiceAddress()->save($practiceAddress);
        return  $practiceAddress;
    }

    /**
     *  Description: This function store practice billing address
     *  1) This method is used to store
     *  2) If Initial id not exit message will return
     *  3) If fields not validate ,field is required message will return
     *  4) In case of fields validate , store practice billing address
     *  5) All field is required
     *  6) Activity is logged, and a success message is return static function add practice address billing & call this function in practiceRequest function
     *
     * @param  mixed $request
     * @param  mixed $practice
     * @return void
     */
    public static function practiceAddressBilling($request, $practice)
    {
        $practiceAddressBilling = new PracticeBillingAddress;
        $practiceAddressBilling->practice_id = $practice['id']; //practice id
        $practiceAddressBilling->billing_address_line_1 = $request['billing_address_line_1'];
        $practiceAddressBilling->billing_address_line_2 = $request['billing_address_line_2'];
        $practiceAddressBilling->billing_country_id = $request['billing_country_id'];
        $practiceAddressBilling->billing_city_id = $request['billing_city_id'];
        $practiceAddressBilling->billing_state_id = $request['billing_state_id'];
        $practiceAddressBilling->billing_zip_code = $request['billing_zip_code'];
        $practiceAddressBilling->billing_lat = $request['billing_lat'];
        $practiceAddressBilling->billing_lng = $request['billing_lng'];
        $practice->practiceBillingAddress()->save($practiceAddressBilling);
        return $practiceAddressBilling;
    }

    /**
     * Description: Retrieving all practice notifications
     *  1) Response with notifications array is returned
     *
     * @param $request
     * @return Response
     */
    public function allNotifications($request): Response
    {
        $practice = Practice::where('id', $this->practice_id() )->first();
        $notifications = $practice->notifications()
            ->paginate($request->pagination);

        $notifications->setCollection($notifications->groupBy(
            fn($query) => Carbon::parse($query->created_at)->format('d M Y')
        ));

        $notifications['unread'] = $practice->unreadNotifications()->count();
        return $this->response(null, $notifications, PGMBook::SUCCESS['NOTIFICATIONS'],200);
    }

    /**
     * Description: Marking practice notification as read
     *  1) Notification is marked as read
     *  2) Unread notifications count is returned as response
     *  3) Notification is logged, and it's response is returned
     *
     * @param Request $request
     * @return Response
     */
    public function markNotificationAsRead($request): Response
    {
        $practice = Practice::where('id', $this->practice_id())->first();
        $notification = $practice->notifications()->where('id', $request->notification_id)->first();

        if (!$notification)
        {
            $response = $this->response($request->notification_id, null, PGMBook::FAILED['NOTIFICATIONS_NOT_FOUND'],404);
        }
        else
        {
            $notification->markAsRead();
            $unreadNotificationsCount = $practice->unreadNotifications()->count();
            $response = $this->response($request->notification_id, $unreadNotificationsCount, PGMBook::SUCCESS['NOTIFICATION_READ'],200);
        }

        return $response;
    }

    /**
     * Description: Marking practice all notifications as read
     *  1) All notifications is marked as read
     *  2) Notification is logged, and it's response is returned
     *
     * @param Request $request
     * @return Response
     */
    public function markAllNotificationsAsRead($request): Response
    {
        $practice = Practice::where('id', $this->practice_id())->first();
        $notifications = $practice->unreadNotifications() ? $practice->unreadNotifications()->update(['read_at' => now()]) : null ;

        return $this->response($this->practice_id(), $notifications, PGMBook::SUCCESS['ALL_NOTIFICATIONS_MARKED_AS_READ'],200);
    }


      /**
     *  Description: This function check contact person email
     *  1) This method is used to store
     *  2) If email  exit or not  message will return
     *  4) Activity is logged, and a success message is return
     * @param  mixed $request
     */


    public function contactPersonEmailCheck($request): Response
    {
        $data = $request->input();
        $message= null;
        $status= 200;
        $success = true;
        foreach ($data['contacts'] as $contact) {
            $practiceContact = PracticeContact::where('email', $contact['email'])->first();
            if ($practiceContact) {
                $unique = false;
                $message = PGMBook::FAILED['EMAIL_EXISTS'];
                $status = 409;
                $success = false;
            }
        }
        return $this->response(true, null, $message, $status, $success);

    }

}

<?php

namespace App\Traits;

use App\Helper\Helper;
use Symfony\Component\HttpFoundation\Response as StatusResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


trait RespondsWithHttpStatus
{
    /**
     * Description: Store activity log. Return response.
     * 1) Store activity log
     * 2) Return success response data and message
     *
     * @param  mixed $message
     * @param  mixed $request
     * @param  mixed $response
     * @param  mixed $responseMessage
     * @param  mixed $data
     * @param  mixed $status
     * @return Response
     */
    protected function success($message, $request, $response, $responseMessage, $data, $status): Response
    {
        Helper::activityLog($message, json_encode($request), json_encode($response));

        return response([
            'success' => true,
            'message' => $responseMessage,
            'data' => $data,
        ], $status);
    }

    /**
     * Description: Identify auth user. Store activity log. Return response
     * 1) Identify user and get key
     * 2) Store activity log
     * 3) Return success response true/false, data and message
     *
     * @param $request_data
     * @param $response_data
     * @param $response_message
     * @param mixed $status
     * @param bool $success
     * @return Response
     */
    protected function response($request_data, $response_data, $response_message, mixed $status, bool $success = true): Response
    {
        // Get user key. Generate message for log
        $message = $this->uniqueKey() . '-' . $response_message;

        // Store activity log
        Helper::activityLog($message, json_encode($request_data), json_encode($response_data));

        return response([
            'success' => $success,
            'message' => $response_message,
            'data' => $response_data,
        ], $status);
    }

    /**
     * Description: Return success response data and message
     *
     * @param  mixed $message
     * @param  mixed $data
     * @param  mixed $status
     * @return Response
     */
    protected function successWithoutActivityLog($message, $data, $status): Response
    {
        return response([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Description: Return error response
     *
     * @param  mixed $message
     * @param  mixed $status
     * @return Response
     */
    protected function error($message, $status): Response
    {
        return response([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    /**
     * Description: Log exceptions. Return error.
     * 1) Exception log
     * 2) Return error response
     *
     * @param  mixed $exception
     * @return void
     */
    protected function exception($exception)
    {
        $message = $this->isHttpException($exception) ? StatusResponse::$statusTexts[$exception->getStatusCode()] : $exception->getMessage();
        $status = $this->isHttpException($exception) ? $exception->getStatusCode() : 500;

        Helper::exceptionLogs(__METHOD__, $exception, $message, $status);
        return response()->json(['error' => $message], $status);
    }

    /**
     * Description: Store activity logs
     *
     * @param  mixed $message
     * @param  mixed $request
     * @param  mixed $response
     * @return void
     */
    protected function activityLogs($message, $request, $response): void
    {
        Helper::activityLog($message, json_encode($request), json_encode($response));
    }

    /**
     * Description: Identify auth user
     *
     * @return void
     */
    public function uniqueKey()
    {
        $authDriver = Auth::getDefaultDriver();
        $authDriver == 'api' ? $auth_key = auth()->user()->user_key :
        ($authDriver == 'practice-api' ? $auth_key = auth()->user()->practice_key :
        ($authDriver == 'doctor-api' ? $auth_key = auth()->user()->doctor_key :
        ($authDriver == 'patient-api' ? $auth_key = auth()->user()->patient_key :
        ($authDriver == 'superAdmin-api' ?  $auth_key = auth()->user()->name :
        ($authDriver == 'web' ? $auth_key = "web" : $auth_key = null )))));
        return $auth_key;
    }

    public function practice_id()
    {
        $practiceID = Auth::getDefaultDriver();
        $practiceID ==  'practice-api' ? $practiceID = auth()->guard('practice-api')->id() :
        ($practiceID == 'doctor-api' ? $practiceID = auth()->guard('doctor-api')->user()->practice_id :
        ($practiceID == 'api' ? $practiceID = auth()->guard('api')->user()->practice_id : null ));
        return $practiceID;
    }
}

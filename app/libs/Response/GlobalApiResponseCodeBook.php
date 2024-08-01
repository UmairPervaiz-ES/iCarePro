<?php

namespace App\libs\Response;

class GlobalApiResponseCodeBook
{
    const SUCCESS = [
        'outcome' => 'SUCCESS',
        'outcomeCode' => 0,
        'httpResponseCode' => 200,
        'statusResponse' => 'success',
    ];

    const RECORD_CREATED = [
        'outcome' => 'RECORD_CREATED',
        'outcomeCode' => 1,
        'httpResponseCode' => 201,
        'statusResponse' => 'record-created',
    ];

    const RECORD_NOT_EXIST = [
        'outcome' => 'RECORD_NOT_EXIST',
        'outcomeCode' => 2,
        'httpResponseCode' => 204,
        'statusResponse' => 'record-not-exist',
    ];

    const BAD_REQUEST = [
        'outcome' => 'BAD_REQUEST',
        'outcomeCode' => 3,
        'httpResponseCode' => 400,
        'statusResponse' => 'bad-request',
    ];

    const UNAUTHORIZED = [
        'outcome' => 'UNAUTHORIZED',
        'outcomeCode' => 4,
        'httpResponseCode' => 401,
        'statusResponse' => 'unauthorized',
    ];

    const INVALID_CREDENTIALS = [
        'outcome' => 'INVALID_CREDENTIALS',
        'outcomeCode' => 5,
        'httpResponseCode' => 402,
        'statusResponse' => 'invalid-credentials',
    ];

    const FORBIDDEN = [
        'outcome' => 'FORBIDDEN',
        'outcomeCode' => 6,
        'httpResponseCode' => 403,
        'statusResponse' => 'forbidden',
    ];

    const NOT_FOUND = [
        'outcome' => 'NOT_FOUND',
        'outcomeCode' => 7,
        'httpResponseCode' => 404,
        'statusResponse' => 'not-found',
    ];

    const CONSENT_REQUIRED = [
        'outcome' => 'CONSENT_REQUIRED',
        'outcomeCode' => 8,
        'httpResponseCode' => 412,
        'statusResponse' => 'consent-required',
    ];

    const UNPROCESSABLE_ENTITY = [
        'outcome' => 'UNPROCESSABLE_ENTITY',
        'outcomeCode' => 9,
        'httpResponseCode' => 422,
        'statusResponse' => 'unprocessable-entity',
    ];

    const INTERNAL_SERVER_ERROR = [
        'outcome' => 'INTERNAL_SERVER_ERROR',
        'outcomeCode' => 10,
        'httpResponseCode' => 500,
        'statusResponse' => 'internal-server-error',
    ];

    const INSUFFICIENT = [
        'outcome' => 'INSUFFICIENT_BALANCE',
        'outcomeCode' => 11,
        'httpResponseCode' => 452,
        'statusResponse' => 'INSUFFICIENT_BALANCE',
    ];

    const TOO_MANY_REQUESTS = [
        'outcome' => 'TOO_MANY_Requests',
        'outcomeCode' => 12,
        'httpResponseCode' => 429,
        'statusResponse' => 'TOO_MANY_Requests',
    ];

}

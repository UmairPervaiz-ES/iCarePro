<?php

namespace App\libs\Messages;

class SuperAdminGlobalMessageBook
{

    const FAILED = [
        'INVALID_CREDENTIALS' => 'Invalid credentials!',
        'PRACTICE_REQUEST_NOT_FOUND' => 'Practice registration request does not exist.',
    ];
    const SUCCESS = [
        'REGISTER' => 'You are registered successfully as a admin.',
        'LOGGED_IN' => 'logged in successfully.',
        'DASHBOARD_STATS' => 'Dashboard Stats',
        'INITIAL_PENDING' => 'Initial practice requests',
        'PRACTICE_REGISTER_EMAIL' => 'Practice request is accepted send credentials by email.',
        'SEND_EMAIL' => 'Super admin send the response to practice request by email.',
        'PRACTICE_LIST' => 'Practice registration list received.',
        'PRACTICE_DETAILS' => 'Practice details.',
    ];
}

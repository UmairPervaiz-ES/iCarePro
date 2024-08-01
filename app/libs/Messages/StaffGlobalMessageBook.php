<?php

namespace App\libs\Messages;

class StaffGlobalMessageBook
{
    const FAILED = [
        'INVALID_CREDENTIALS' => 'Invalid credentials!',
        'STAFF_NOT_FOUND' => 'User not found.',

    ];
    const SUCCESS = [
        'LOGGED_IN' => 'logged in successfully.',
        'CHANGE_PASSWORD' => 'Password changed successfully.',
        'RESET_PASSWORD'=>'Password reset successfully.',
        'LOGOUT' => 'Logout successfully.',
    ];
}

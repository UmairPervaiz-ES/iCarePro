<?php
$constants = [];
require('constants/dev_constants.php');
require('constants/test_constants.php');
require('constants/live_constants.php');
require('constants/job_queues.php');
require('constants/email_constants.php');
require('constants/outlook_dev.php');

$host = env('APP_ENV');
if ($host == "test") {
    return array_merge($test_constants, $job_queues, $email_constants, $constants, $dev_patient);
}
if ($host == "production") {
    return array_merge($live_constants, $job_queues, $email_constants, $constants);
}
if ($host == 'dev') {
    return array_merge($dev_constants, $job_queues, $email_constants, $constants, $dev_patient);
}
if ($host == 'local') {
    return array_merge($dev_constants, $job_queues, $email_constants, $constants, $dev_patient);
}
if ($host == 'QA') {
    return array_merge($dev_constants, $job_queues, $email_constants, $constants, $dev_patient);
}
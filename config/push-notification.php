<?php

return [
    'device_platform' => [
        'android' => 0,
        'ios' => 1,
    ],
    'topic_arn' => env('TOPIC_ARN', ''),
    'android_application_arn' => env('ANDROID_APPLICATION_ARN', ''),
    'ios_application_arn' => env('IOS_APPLICATION_ARN', ''),
];
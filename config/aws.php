<?php

return [
    's3_url' => env('AWS_URL'),
    's3_root_prefix' => env('AWS_S3_PREFIX'),
    's3_folder' => [
    ],
    'firebase_api_url' => env('FIREBASE_API_URL'),
    'firebase_private_key' => env('FIREBASE_PRIVATE_VAPID_KEY'),
    'push_notification_title' => env('PUSH_NOTIFICATION_TITLE'),
    'sqs_queue_name_prefix' => env('QUEUE_NAME_PREFIX'),
];

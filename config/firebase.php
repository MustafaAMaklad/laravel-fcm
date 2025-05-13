<?php

return [
    'credentials' => [
        'token_uri' => env('FIREBASE_TOKEN_URI'),
        'client_id' => env('FIREBASE_CLIENT_ID'),
        'client_email' => env('FIREBASE_CLIENT_EMAIL'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'private_key' => env('FIREBASE_PRIVATE_KEY'),
    ],
    'scopes' => [
        'fcm' =>  env('FCM_SCOPE', 'https://www.googleapis.com/auth/firebase.messaging')
    ],
    'messages' => [
        'send' => 'https://fcm.googleapis.com/v1/projects/' . env('FIREBASE_PROJECT_ID') .'/messages:send',
    ]
]; 
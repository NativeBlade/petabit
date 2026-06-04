<?php

return [
    /*
    | Base URL of the petabit-server API.
    | - Desktop dev:        http://127.0.0.1:8000
    | - Android emulator:   http://10.0.2.2:8000
    | - Physical device:    http://<your-LAN-ip>:8000
    */
    'api_url' => env('PETABIT_API_URL', "https://pet4bit.com"),
];

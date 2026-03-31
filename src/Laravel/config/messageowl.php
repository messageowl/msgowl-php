<?php

return [
    'api_key'        => env('MESSAGEOWL_API_KEY'),
    'timeout'        => env('MESSAGEOWL_TIMEOUT', 30),
    'use_query_auth' => env('MESSAGEOWL_USE_QUERY_AUTH', false),
];

<?php

return [
    'supports_credentials' => true,
    'allowed_headers' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
    'allowed_origins_patterns' => [],
    'exposed_headers' => [],
    'max_age' => 0,
];

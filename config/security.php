<?php

return [
    'api_encryption_key' => env('API_ENCRYPTION_KEY'),
    'api_hash_algorithm' => env('API_HASH_ALGORITHM', 'sha256'),
    'max_login_attempts' => env('MAX_LOGIN_ATTEMPTS', 5),
    'login_decay_minutes' => env('LOGIN_DECAY_MINUTES', 15),
    'session_timeout' => env('SESSION_TIMEOUT', 120),
    'require_https' => env('FORCE_HTTPS', true),
    'enable_cors' => env('ENABLE_CORS', true),
    'cors_allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
];

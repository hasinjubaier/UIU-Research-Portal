<?php
/**
 * Application Settings
 */

return [
    'app' => [
        'name'  => $_ENV['APP_NAME'] ?? 'UIU Research Portal',
        'env'   => $_ENV['APP_ENV'] ?? 'development',
        'url'   => $_ENV['APP_URL'] ?? 'http://localhost:8000',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN),
    ],

    'jwt' => [
        'secret'         => $_ENV['JWT_SECRET'] ?? 'default-secret-key-change-me',
        'algorithm'      => 'HS256',
        'expire'         => (int)($_ENV['JWT_EXPIRE'] ?? 86400),
        'refresh_expire' => (int)($_ENV['JWT_REFRESH_EXPIRE'] ?? 604800),
    ],

    'upload' => [
        'directory'          => __DIR__ . '/../storage/uploads',
        'max_size'           => (int)($_ENV['MAX_FILE_SIZE'] ?? 52428800), // 50MB
        'allowed_extensions' => explode(',', $_ENV['ALLOWED_EXTENSIONS'] ?? 'pdf,doc,docx,ppt,pptx,xls,xlsx,csv,zip,png,jpg,jpeg'),
    ],

    'cors' => [
        'allowed_origins' => explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? 'http://localhost:8000,http://127.0.0.1:8000'),
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],
        'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With', 'Accept', 'Origin'],
    ],

    'pagination' => [
        'default_page'     => 1,
        'default_per_page' => 20,
        'max_per_page'     => 100,
    ],
];

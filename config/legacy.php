<?php

return [
    'apps' => [
        'codeigniter_base_url' => env('LEGACY_CODEIGNITER_BASE_URL', 'http://localhost/tntlaravel/cmsc/'),
        'laravel_base_url' => env('LEGACY_LARAVEL_BASE_URL', 'http://localhost/tntlaravel/cmslv'),
    ],

    'database' => [
        'host' => env('LEGACY_DB_HOST', 'localhost'),
        'port' => (int) env('LEGACY_DB_PORT', 3306),
        'name' => env('LEGACY_DB_DATABASE', env('DB_DATABASE', 'tntlaravel')),
    ],

    'session' => [
        'branch_keys' => ['brc_id', 'branch_id'],
        'academic_session_keys' => ['session_id', 'academic_session_id'],
        'financial_year_keys' => ['financial_year_id', 'year_id'],
    ],

    'status' => [
        'active_values' => ['1', 'yes', 'active'],
    ],

    'uploads' => [
        'staff_images' => 'uploads/staff_images',
        'staff_documents' => 'uploads/staff_documents',
        'manual_hrm_documents' => 'uploads/manual_hrmdocuments',
    ],

    'routes' => [
        'staff_login' => ['login', 'staff/login', 'superadmin/login'],
        'site_login' => ['signin', 'site/signin'],
    ],
];

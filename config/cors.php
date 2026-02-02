<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'register',
        'login',
        'logout'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5174',
        'http://127.0.0.1:5174',

        // FRONTEND VERCEL (⚠️ très important)
        'https://frontend-red-prod-full.vercel.app',
        'https://frontend-red-prod-full-4jgsaany0-antas-projects-04ba10c1.vercel.app',
    ],

    'allowed_origins_patterns' => [
        '^https://.*\.vercel\.app$'
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'supports_credentials' => true,

    'max_age' => 0,
];

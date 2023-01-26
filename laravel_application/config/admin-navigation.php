<?php

return [
    [
        // Frontend
        'title' => 'Frontend <span class="text-sm">['.env('APP_URL').']</span>',
        'icon' => 'bi bi-house-fill',
        'route' => 'home',
    ],[
        // Seperator
        'seperator' => true,
    ],[
        // Dashboard
        'title' => 'Dashboard',
        'icon' => 'bi bi-speedometer',
        'route' => 'admin.dashboard',
    ],[
        // Management model (User)
        'manageableModel' => [
            'title' => 'Users',
            'icon' => 'bi bi-people-fill',
            'model' => \App\Models\User::class,
            'route' => 'admin.users',
        ],
    ],[
        // Management model (Application Config)
        'manageableModel' => [
            'title' => 'Application Config',
            'icon' => 'bi bi-gear-fill',
            'model' => \App\Models\ApplicationConfig::class,
            'route' => 'admin.application-config',
        ]
    ],[
        // Seperator
        'seperator' => true,
    ],[
        // Manage accoutn
        'title' => 'Manage account',
        'icon' => 'bi bi-person-lines-fill',
        'route' => 'admin.account.manage',
    ],[
        // Logout
        'title' => 'Logout',
        'icon' => 'bi bi-door-closed-fill',
        'route' => 'logout',
    ],
];

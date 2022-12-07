<?php

use \Illuminate\Support\Facades\Route;

return [
    [
        'title' => 'Frontend <span class="text-sm">['.env('APP_URL').']</span>',
        'icon' => 'bi bi-house-fill',
        'route' => 'home',
    ],[
        'seperator' => true,
    ],[
        'title' => 'Dashboard',
        'icon' => 'bi bi-speedometer',
        'route' => 'admin.dashboard',
    ],[
        'manageableModels' => true,
    ],[
        'title' => 'Settings',
        'icon' => 'bi bi-gear-fill',
        'route' => 'home',
        'children' => [
            [
                'title' => 'General',
                'route' => 'home',
            ],
            [
                'title' => 'Security',
                'route' => 'home',
            ],
            [
                'title' => 'Notifications',
                'route' => 'home',
            ],
        ],
    ],[
        'seperator' => true,
    ],[
        'title' => 'Logout',
        'icon' => 'bi bi-door-closed-fill',
        'route' => 'admin.logout',
    ],
];

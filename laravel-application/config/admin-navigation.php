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
        'seperator' => true,
    ],[
        'title' => 'Logout',
        'icon' => 'bi bi-door-closed-fill',
        'route' => 'admin.logout',
    ],
];

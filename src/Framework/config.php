<?php

use PermissionGenerator\Framework\LaravelConfigLoader;
use PermissionGenerator\Infra\LaravelJsonPermissionRepository;

return [
    'roles' => ['permissions'],
    'directories' => [
        app_path(),
        resource_path('views'),
    ],
    'output' => resource_path('role'),
    'extensions' => ['php'],
    'container' => [
        'config_loader' => LaravelConfigLoader::class,
        'permission_repository' => LaravelJsonPermissionRepository::class,
    ],
];

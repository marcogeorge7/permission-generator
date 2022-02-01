<?php

namespace ephunk\permissiongenerator\Generator;

interface PermissionRepository
{
    public function exists(Permission $permission, string $role): bool;

    public function save(Permission $permission, string $role): void;
}

<?php

namespace PermissionGenerator\Generator;

use PermissionGenerator\Generator\Exception\InvalidDirectoriesConfiguration;
use PermissionGenerator\Generator\Exception\InvalidExtensionsConfiguration;

class PermissionService
{
    private ConfigLoader $config;
    private PermissionScanner $scanner;
    private PermissionRepository $repository;

    public function __construct(ConfigLoader $config, PermissionScanner $scanner, PermissionRepository $repository)
    {
        $this->config = $config;
        $this->scanner = $scanner;
        $this->repository = $repository;
    }

    /**
     * @throws InvalidDirectoriesConfiguration
     * @throws InvalidExtensionsConfiguration
     */
    public function scanAndSaveNewKeys(): void
    {
        $directories = $this->config->directories();
        $extensions = $this->config->extensions();

        $permissions = $this->scanner->scan($extensions, $directories);

        $this->storePermissions($permissions);
    }

    /**
     * @param Permission[] $permissions
     */
    private function storePermissions(array $permissions): void
    {
        array_map(function (Permission $permission): void {
            $this->storePermission($permission);
        }, $permissions);
    }

    private function storePermission(Permission $permission): void
    {
        $roles = $this->config->roles();

        array_map(function (string $role) use ($permission): void {
            if ($this->repository->exists($permission, $role)) {
                return;
            }

            $this->repository->save($permission, $role);
        }, $roles);
    }
}

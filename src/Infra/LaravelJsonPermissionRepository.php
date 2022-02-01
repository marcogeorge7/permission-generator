<?php

namespace Generator\Infra;

use Generator\Infra\Exception\InvalidPermissionFile;
use Generator\Infra\Exception\PermissionFileDoesNotExistForRole;
use Generator\Infra\Exception\UnableToSavePermissionKeyAlreadyExists;
use Generator\Generator\ConfigLoader;
use Generator\Generator\Permission;
use Generator\Generator\PermissionRepository;

class LaravelJsonPermissionRepository implements PermissionRepository
{
    private ConfigLoader $config;

    /** @var array<string, array<string>> */
    private array $fileCache = [];

    public function __construct(ConfigLoader $config)
    {
        $this->config = $config;
    }

    /**
     * @throws PermissionFileDoesNotExistForRole
     * @throws InvalidPermissionFile
     */
    public function exists(Permission $permission, string $role): bool
    {
        $permissions = $this->getPermissions($role);

        return isset($permissions[$permission->getKey()]);
    }

    /**
     * @throws InvalidPermissionFile
     * @throws PermissionFileDoesNotExistForRole
     * @throws UnableToSavePermissionKeyAlreadyExists
     */
    public function save(Permission $permission, string $role): void
    {
        $permissions = $this->getPermissions($role);

        if ($this->exists($permission, $role)) {
            throw new UnableToSavePermissionKeyAlreadyExists($permission, $role);
        }

        $permissions[$permission->getKey()] = $permission->getValue();

        $this->fileCache[$role] = $permissions;

        $this->writeFile($role);
    }

    /**
     * @throws PermissionFileDoesNotExistForrole
     * @throws InvalidPermissionFile
     * @return array<string>
     */
    private function getPermissions(string $role): array
    {
        if (!isset($this->fileCache[$role])) {
            $this->fileCache[$role] = $this->readFile($role);
        }

        return $this->fileCache[$role];
    }

    private function getFileNameForRole(string $role): string
    {
        $directory = $this->config->output();

        return $directory . "/{$role}.json";
    }

    /**
     * @return string[]
     * @throws InvalidPermissionFile
     * @throws PermissionFileDoesNotExistForRole
     */
    private function readFile(string $role): array
    {
        $filename = $this->getFileNameForRole($role);

        if (!file_exists($filename)) {
            throw new PermissionFileDoesNotExistForRole($role);
        }

        $content = file_get_contents($filename);

        if (!$content) {
            throw new InvalidPermissionFile($role);
        }

        $permissions = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidPermissionFile($role);
        }

        return $permissions;
    }

    private function writeFile(string $role): void
    {
        $content = $this->fileCache[$role];
        ksort($content);

        file_put_contents(
            $this->getFileNameForRole($role),
            json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}

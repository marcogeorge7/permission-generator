<?php

namespace ephunk\permissiongenerator\Infra\Exception;

use Exception;

class PermissionFileDoesNotExistForRole extends Exception
{
    public function __construct(string $role)
    {
        parent::__construct(sprintf("Permission file does not exist for %s", $role));
    }
}

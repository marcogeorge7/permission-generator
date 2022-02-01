<?php

namespace ephunk\permissiongenerator\Infra\Exception;

use Exception;

class InvalidPermissionFile extends Exception
{
    public function __construct(string $role)
    {
        parent::__construct(sprintf('Invalid permission file for role %s', $role));
    }
}

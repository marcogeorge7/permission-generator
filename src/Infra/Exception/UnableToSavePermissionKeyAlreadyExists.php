<?php

namespace PermissionGenerator\Infra\Exception;

use Exception;
use PermissionGenerator\Generator\Permission;

class UnableToSavePermissionKeyAlreadyExists extends Exception
{
    public function __construct(Permission $permission, string $role)
    {
        parent::__construct(
            sprintf(
                'Unable to save permission, key %s already for role %s',
                $permission->getKey(),
                $role
            )
        );
    }
}

<?php

namespace ephunk\permissiongenerator\Generator\Exception;

use Exception;

class InvalidExtensionsConfiguration extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid extensions configuration');
    }
}

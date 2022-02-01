<?php

namespace ephunk\permissiongenerator\Generator\Exception;

use Exception;

class InvalidDirectoriesConfiguration extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid directories configuration');
    }
}

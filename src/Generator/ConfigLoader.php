<?php

namespace PermissionGenerator\Generator;

interface ConfigLoader
{
    /**
     * Load the list of project roles
     *
     * @return array<string>
     */
    public function roles(): array;

    /**
     * Load the list of directories to be scanned
     *
     * @return array<string>
     */
    public function directories(): array;

    /**
     * Load the directory where the updated permission file will be written
     */
    public function output(): string;

    /**
     * Load the list of file extensions to be scanned
     *
     * @return array<string>
     */
    public function extensions(): array;
}

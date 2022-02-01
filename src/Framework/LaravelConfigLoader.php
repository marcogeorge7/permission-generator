<?php

namespace ephunk\permissiongenerator\Framework;

use ephunk\permissiongenerator\Generator\ConfigLoader;

class LaravelConfigLoader implements ConfigLoader
{
    /**
     * @inheritDoc
     */
    public function roles(): array
    {
        return $this->loadConfigInArray('roles');
    }

    /**
     * @inheritDoc
     */
    public function directories(): array
    {
        return $this->loadConfigInArray('directories');
    }

    /**
     * @inheritDoc
     */
    public function output(): string
    {
        return $this->loadConfigInString('output');
    }

    /**
     * @inheritDoc
     */
    public function extensions(): array
    {
        return $this->loadConfigInArray('extensions');
    }

    /**
     * @return array<string>
     */
    private function loadConfigInArray(string $key): array
    {
        $values = $this->load($key);

        if (!is_array($values)) {
            return [];
        }

        return $values;
    }

    private function loadConfigInString(string $key): string
    {
        $value = $this->load($key);

        if (!is_string($value)) {
            return '';
        }

        return $value;
    }

    /**
     * @return string|string[]
     */
    private function load(string $key)
    {
        return config("generator.{$key}");
    }
}

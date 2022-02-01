<?php

namespace PermissionGenerator\Framework;

use Illuminate\Console\Command;
use PermissionGenerator\Generator\PermissionService;

/**
 * @codeCoverageIgnore
 */
class GeneratorCommand extends Command
{
    /** @inheritdoc */
    protected $signature = 'generator:update';

    /** @inheritdoc */
    protected $description = 'Search new keys and update permission file';

    private PermissionService $service;

    public function __construct(PermissionService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    public function handle(): void
    {
        $this->service->scanAndSaveNewKeys();
    }
}

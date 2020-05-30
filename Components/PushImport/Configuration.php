<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\PushImport;

class Configuration
{
    /** @var array */
    private $pluginConfig;

    public function __construct(array $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function isPushImportEnabled(): bool
    {
        return (bool) $this->pluginConfig['ffAutomaticPushImport'];
    }

    public function getImportTypes(): array
    {
        return (array) $this->pluginConfig['ffImportDataTypes'];
    }
}

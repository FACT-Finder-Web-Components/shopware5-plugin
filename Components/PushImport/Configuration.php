<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\PushImport;

use Shopware\Components\Plugin\ConfigReader;

class Configuration
{
    /** @var string */
    private $pluginName;

    /** @var ConfigReader */
    private $configReader;

    public function __construct(string $pluginName, ConfigReader $configReader)
    {
        $this->pluginName   = $pluginName;
        $this->configReader = $configReader;
    }

    public function isPushImportEnabled(): bool
    {
        return  (bool) $this->configReader->getByPluginName($this->pluginName)['ffAutomaticPushImport'];
    }

    public function getImportTypes(): array
    {
        return $this->configReader->getByPluginName($this->pluginName)['ffImportDataTypes'];
    }
}

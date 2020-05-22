<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Upload;

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

    public function getUrl(): string
    {
        return $this->configReader->getByPluginName($this->pluginName)['ffFtpUrl'];
    }

    public function getUserName(): string
    {
        return $this->configReader->getByPluginName($this->pluginName)['ffFtpUser'];
    }

    public function getPassword(): string
    {
        return $this->configReader->getByPluginName($this->pluginName)['ffFtpPassword'];
    }
}

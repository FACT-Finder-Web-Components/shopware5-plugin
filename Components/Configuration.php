<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components;

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

    public function isEnabled(): bool
    {
        return (bool) $this->configReader->getByPluginName($this->pluginName)['ffEnabled'];
    }

    public function useForCategories(): bool
    {
        return $this->isEnabled() && $this->configReader->getByPluginName($this->pluginName)['ffUseForCategories'];
    }

    public function getServerUrl(): string
    {
        return rtrim($this->configReader->getByPluginName($this->pluginName)['ffServerUrl'], ' /');
    }

    public function getChannel(): string
    {
        return $this->configReader->getByPluginName($this->pluginName)['ffChannel'];
    }

    public function getAuthorization(): string
    {
        ['ffUser' => $user, 'ffPassword' => $password] = $this->configReader->getByPluginName($this->pluginName);
        return 'Basic ' . base64_encode("{$user}:{$password}");
    }
}

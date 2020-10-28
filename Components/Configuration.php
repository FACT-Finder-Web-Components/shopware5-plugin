<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components;

use OmikronFactfinder\Components\Api\Credentials;

class Configuration
{
    /** @var array */
    private $pluginConfig;

    public function __construct(array $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->pluginConfig['ffEnabled'] ?? false);
    }

    public function useForCategories(): bool
    {
        return $this->isEnabled() && ($this->pluginConfig['ffUseForCategories'] ?? false);
    }

    public function getServerUrl(): string
    {
        return rtrim($this->pluginConfig['ffServerUrl'] ?? '', ' /');
    }

    public function getChannel(): string
    {
        return $this->pluginConfig['ffChannel'] ?? '';
    }

    public function getCredentials(): Credentials
    {
        return new Credentials($this->pluginConfig['ffUser'] ?? '', $this->pluginConfig['ffPassword'] ?? '');
    }
}

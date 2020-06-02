<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Upload;

class Configuration
{
    /** @var array */
    private $pluginConfig;

    public function __construct(array $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function getUrl(): string
    {
        return (string) $this->pluginConfig['ffFtpUrl'];
    }

    public function getUserName(): string
    {
        return (string) $this->pluginConfig['ffFtpUser'];
    }

    public function getPassword(): string
    {
        return (string) $this->pluginConfig['ffFtpPassword'];
    }
}

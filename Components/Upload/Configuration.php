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
        return (string) $this->pluginConfig['ffFtpHost'];
    }

    public function getUserName(): string
    {
        return (string) $this->pluginConfig['ffFtpUser'];
    }

    public function getPassword(): string
    {
        return (string) $this->pluginConfig['ffFtpPassword'];
    }

    public function getProtocol(): string
    {
        return (string) $this->pluginConfig['ffFtpProtocol'];
    }

    public function getPort(): int
    {
        return $this->pluginConfig['ffFtpPort'] ? (int) $this->pluginConfig['ffFtpPort'] : 22;
    }

    public function getAuthType(): string
    {
        return (string) $this->pluginConfig['ffFtpAuthType'];
    }

    public function getPrivateKey(): string
    {
        return (string) $this->pluginConfig['ffFtpKeyFile'];
    }

    public function getRootDir(): string
    {
        return !empty($this->pluginConfig['ffFtpRootDir']) ? DIRECTORY_SEPARATOR . $this->pluginConfig['ffFtpRootDir'] : DIRECTORY_SEPARATOR;
    }
}

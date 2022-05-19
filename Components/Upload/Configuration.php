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
        return $this->getProtocol() === 'sftp' ? 22 : ($this->pluginConfig['ffFtpPort'] ?: 21);
    }

    public function getPrivateKey(): string
    {
        return (string) $this->pluginConfig['ffFtpKeyFile'];
    }

    public function getRootDir(): string
    {
        //TODO: because of empty input returns string with value "null" I have add this check
        if ($this->pluginConfig['ffFtpRootDir'] === 'null') {
            return DIRECTORY_SEPARATOR;
        }

        return DIRECTORY_SEPARATOR . $this->pluginConfig['ffFtpRootDir'];
    }

    public function getKeyPassphrase(): string
    {
        return $this->pluginConfig['ffFtpKeyPassphrase'] ?? '';
    }
}

<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Upload\Configuration as FTPConfig;
use Shopware\Components\Filesystem\FilesystemFactory;

class UploadService
{
    /** @var FTPConfig */
    private $ftpConfig;

    /** @var FilesystemFactory */
    private $fsFactory;

    public function __construct(FilesystemFactory $filesystemFactory, FTPConfig $configuration)
    {
        $this->fsFactory = $filesystemFactory;
        $this->ftpConfig = $configuration;
    }

    public function uploadFeed(string $path, string $contents): void
    {
        if ($this->config()['type'] === 'sftp') {
            $filesystem = new SftpService($this->ftpConfig);
            $filesystem->write($path, $contents);
            return;
        }
        //ftp
        $filesystem = $this->fsFactory->factory($this->config());
        $filesystem->getConfig()->set('disable_asserts', true);
        $filesystem->write($path, $contents);
    }

    public function testConnection(FTPConfig $config): void
    {
        if ($config->getProtocol() === 'sftp') {
            $filesystem = new SftpService($config);
            $filesystem->write('testconnection', 'test');
            return;
        }
        //ftp
        $filesystem = $this->fsFactory->factory($this->config($config));
        $filesystem->write('testconnection', 'test');
    }

    private function config(FTPConfig $ftpConfig = null): array
    {
        return [
            'config' => [
                'host'     => $ftpConfig ? $ftpConfig->getUrl() : $this->ftpConfig->getUrl(),
                'username' => $ftpConfig ? $ftpConfig->getUserName() : $this->ftpConfig->getUserName(),
                'password' => $ftpConfig ? $ftpConfig->getPassword() : $this->ftpConfig->getPassword(),
                'port'     => $ftpConfig ? $ftpConfig->getPort() : $this->ftpConfig->getPort(),
                'ssl'      => true,
            ],
            'type'   => $ftpConfig ? $ftpConfig->getProtocol() : $this->ftpConfig->getProtocol(),
        ];
    }
}

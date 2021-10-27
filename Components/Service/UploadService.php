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
            $fs = new SftpService($this->ftpConfig);
            $fs->write($path, $contents);
        } else {
            $fs = $this->fsFactory->factory($this->config());
            $fs->getConfig()->set('disable_asserts', true);
            $fs->write($path, $contents);
        }
    }

    public function testConnection(FTPConfig $config): void
    {
        if ($this->config()['type'] === 'sftp') {
            $fs = new SftpService($config);
            $fs->write('test.txt', 'test');
        } else {
            $fs = $this->fsFactory->factory($this->config());
            $fs->write('test.txt', 'test');
        }
    }

    private function config()
    {
        return [
            'config' => [
                'host'     => $this->ftpConfig->getUrl(),
                'username' => $this->ftpConfig->getUserName(),
                'password' => $this->ftpConfig->getPassword(),
                'ssl'      => true,
            ],
            'type'   => $this->ftpConfig->getProtocol(),
        ];
    }
}

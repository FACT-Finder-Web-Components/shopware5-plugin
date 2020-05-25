<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use League\Flysystem\FilesystemInterface;
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
        /** @var FilesystemInterface $fs */
        $fs = $this->fsFactory->factory($this->config());
        $fs->write($path, $contents);
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
            'type'   => 'ftp',
        ];
    }
}

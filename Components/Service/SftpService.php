<?php

namespace OmikronFactfinder\Components\Service;

use Exception;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use OmikronFactfinder\Components\Upload\Configuration as FTPConfig;

class SftpService
{
    /** @var array */
    private $parameters;
    private $sftp;

    public function __construct(FTPConfig $parameters)
    {
        $this->parameters = $parameters;
        $connectionParams = [
            'host'       => $this->parameters->getUrl(),
            'port'       => $this->parameters->getPort(),
            'username'   => $this->parameters->getUserName(),
            'root'       => $this->parameters->getRootDir(),
            'privateKey' => $this->parameters->getPrivateKey(),
            'password'   => $this->parameters->getPassword(),
            'passphrase' => $this->parameters->getKeyPassphrase(),
        ];

        $this->sftp = new Filesystem(new SftpAdapter($connectionParams));
    }

    public function write($path, $contents): void
    {
        if (!$this->sftp->put($path, $contents)) {
            throw new Exception('Can not write to folder ' . $this->parameters->getRootDir());
        }
    }
}

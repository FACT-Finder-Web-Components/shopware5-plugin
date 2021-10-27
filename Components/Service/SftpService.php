<?php

namespace OmikronFactfinder\Components\Service;

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
            'host'     => $this->parameters->getUrl(),
            'username' => $this->parameters->getUserName(),
            'root'     => $this->parameters->getRootDir(),
        ];

        if ($this->parameters->getAuthType() === 'key') {
            $connectionParams['privateKey'] = $this->parameters->getPrivateKey();
        }

        if ($this->parameters->getAuthType() === 'password') {
            $connectionParams['password'] = $this->parameters->getPassword();
        }

        $this->sftp = new Filesystem(new SftpAdapter($connectionParams));
    }

    public function write($path, $contents): void
    {
        try {
            $this->sftp->put($path, $contents);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}

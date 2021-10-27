<?php

declare(strict_types=1);

use OmikronFactfinder\Components\Configuration;
use OmikronFactfinder\Components\Service\TestConnectionService;
use OmikronFactfinder\Components\Upload\Configuration as FTPConfig;
use Shopware\Components\CSRFWhitelistAware;
use Shopware\Components\HttpClient\RequestException;

class Shopware_Controllers_Backend_Factfinder extends \Enlight_Controller_Action implements CSRFWhitelistAware
{
    private function __(string $text, string $namespace = 'backend/omikron/factfinder'): string
    {
        return $this->container->get('snippets')->getNamespace($namespace)->get($text);
    }

    public function getWhitelistedCSRFActions(): array
    {
        return ['testConnection', 'testFtpConnection'];
    }

    public function preDispatch()
    {
        $this->container->get('front')->Plugins()->ViewRenderer()->setNoRender();
    }

    public function testFtpConnectionAction()
    {
        /** @var \OmikronFactfinder\Components\Service\UploadService $uploadService */
        $uploadService  = $this->container->get(\OmikronFactfinder\Components\Service\UploadService::class);
        $params         = new FTPConfig($this->request->getParams());
        $uploadService->testConnection($params);
        $this->response->setBody('');
    }

    public function testConnectionAction()
    {
        $testConnection = $this->container->get(TestConnectionService::class);
        $params         = new Configuration($this->request->getParams());
        $message        = $this->__('connectionEstablished');

        try {
            $testConnection->execute($params->getServerUrl(), $params->getChannel(), $params->getCredentials());
        } catch (RequestException $e) {
            $message = json_decode((string) $e->getBody(), true)['errorDescription'] ?? $e->getMessage();
        }

        $this->response->setBody($message);
    }
}

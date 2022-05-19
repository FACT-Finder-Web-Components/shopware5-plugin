<?php

declare(strict_types=1);

use OmikronFactfinder\Components\Configuration;
use OmikronFactfinder\Components\Service\TestConnectionService;
use OmikronFactfinder\Components\Service\UpdateFieldRolesService;
use OmikronFactfinder\Components\Service\UploadService;
use OmikronFactfinder\Components\Upload\Configuration as FTPConfig;
use Shopware\Components\CSRFWhitelistAware;
use Shopware\Components\HttpClient\RequestException;
use RuntimeException;

class Shopware_Controllers_Backend_Factfinder extends \Enlight_Controller_Action implements CSRFWhitelistAware
{
    private function __(string $text, string $namespace = 'backend/omikron/factfinder'): string
    {
        return $this->container->get('snippets')->getNamespace($namespace)->get($text);
    }

    public function getWhitelistedCSRFActions(): array
    {
        return ['testConnection', 'testFtpConnection', 'updateFieldRoles'];
    }

    public function preDispatch()
    {
        $this->container->get('front')->Plugins()->ViewRenderer()->setNoRender();
    }

    public function testFtpConnectionAction()
    {
        $message        = $this->__('connectionEstablished');

        /** @var UploadService $uploadService */
        $uploadService  = $this->container->get(UploadService::class);
        $params         = new FTPConfig($this->request->getParams());

        try {
            $uploadService->testConnection($params);
        } catch (Exception $exception) {
            $message        = $exception->getMessage();
        }

        $this->response->setBody($message);
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

    public function updateFieldRolesAction()
    {
        $updateFieldRoles = $this->container->get(UpdateFieldRolesService::class);
        $message          = 'Field Roles updated succesfully';

        try {
            $updateFieldRoles->updateFieldRoles();
        } catch (RuntimeException $e) {
            $message = 'Update FieldRoles failed: Cause ' . $e->getMessage();
        } catch (RequestException $e) {
            $message = json_decode((string) $e->getBody(), true)['errorDescription'] ?? $e->getMessage();
        }

        $this->response->setBody($message);
    }
}

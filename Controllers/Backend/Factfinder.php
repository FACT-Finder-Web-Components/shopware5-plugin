<?php

declare(strict_types=1);

use OmikronFactfinder\Components\Api\Credentials;
use OmikronFactfinder\Components\Service\TestConnectionService;
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
        return ['testConnection'];
    }

    public function preDispatch()
    {
        $this->container->get('front')->Plugins()->ViewRenderer()->setNoRender();
    }

    public function testConnectionAction()
    {
        $testConnection = $this->container->get(TestConnectionService::class);
        $params         = $this->request->getParams();
        $message        = $this->__('connectionEstablished');

        try {
            $testConnection->execute($params['ffServerUrl'], $params['ffChannel'], $this->getCredentials($params));
        } catch (RequestException $e) {
            $message = $e->getBody();
            if ($e->getCode() !== 404 && $e->getCode() !== 503) {
                $message = json_decode($message)->errorDescription;
            }
        }

        $this->response->setBody($message);
    }

    private function getCredentials(array $params): Credentials
    {
        return new Credentials($params['ffUser'], $params['ffPassword']);
    }
}

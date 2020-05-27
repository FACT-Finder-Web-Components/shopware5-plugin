<?php

declare(strict_types=1);

use OmikronFactfinder\Components\Api\Credentials;
use OmikronFactfinder\Components\Service\TestConnectionService;
use Shopware\Components\HttpClient\RequestException;
use Shopware\Components\CSRFWhitelistAware;

class Shopware_Controllers_Backend_TestConnection extends \Enlight_Controller_Action implements CSRFWhitelistAware
{
    public function getWhitelistedCSRFActions(): array
    {
        return ['index'];
    }

    public function preDispatch()
    {
        $this->container->get('front')->Plugins()->ViewRenderer()->setNoRender();
    }

    public function indexAction()
    {
        $testConnection = $this->container->get(TestConnectionService::class);
        $message        = 'Connection has been established';
        $params         = $this->request->getParams();

        try {
            $testConnection->execute($params['ffServerUrl'], $params['ffChannel'], $this->getCredentials($params));
        } catch (RequestException $e) {
            if ($e->getCode() === 404 || $e->getCode() === 503) {
                $message = $e->getBody();
            } else {
                $message = json_decode($e->getBody())->errorDescription;
            }
        } finally {
            $this->response->setBody($message);
        }
    }

    private function getCredentials(array $params): Credentials
    {
        return new Credentials($params['ffUser'], $params['ffPassword']);
    }
}

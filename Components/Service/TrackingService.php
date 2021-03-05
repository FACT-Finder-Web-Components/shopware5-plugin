<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Configuration;
use Psr\Log\LoggerInterface;
use Shopware\Components\HttpClient\HttpClientInterface;
use Shopware\Components\HttpClient\RequestException;

class TrackingService
{
    /** @var Configuration */
    private $config;

    /** @var HttpClientInterface */
    private $httpClient;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(Configuration $config, HttpClientInterface $httpClient, LoggerInterface $logger)
    {
        $this->config     = $config;
        $this->httpClient = $httpClient;
        $this->logger     = $logger;
    }

    public function track(string $event, array $eventData): void
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $endpoint = "{$this->config->getServerUrl()}/rest/v4/track/{$this->config->getChannel()}/{$event}";

        try {
            $this->httpClient->post($endpoint, [
                'Accept'        => 'application/json',
                'Authorization' => $this->config->getCredentials(),
                'Content-Type'  => 'application/json',
            ], json_encode($eventData));
        } catch (RequestException $e) {
            $this->logger->error('FACT-Finder tracking error: ' . $e->getBody());
        }
    }
}

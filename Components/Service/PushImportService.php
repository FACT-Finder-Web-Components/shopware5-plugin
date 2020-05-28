<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Configuration;
use OmikronFactfinder\Components\PushImport\Configuration as PushImportConfiguration;
use Shopware\Components\HttpClient\GuzzleHttpClient;
use Shopware\Components\HttpClient\RequestException;

class PushImportService
{
    /** @var GuzzleHttpClient */
    private $client;

    /** @var Configuration */
    private $configuration;

    /** @var PushImportConfiguration */
    private $pushImportConfiguration;

    public function __construct(
        GuzzleHttpClient $client,
        Configuration $configuration,
        PushImportConfiguration $pushImportConfiguration
    ) {
        $this->client                  = $client;
        $this->configuration           = $configuration;
        $this->pushImportConfiguration = $pushImportConfiguration;
    }

    /**
     * @return bool
     * @throws RequestException
     */
    public function execute(): bool
    {
        if (!$this->pushImportConfiguration->isPushImportEnabled()) {
            return false;
        }

        if ($this->isRunning()) {
            throw new RequestException('Push import is currently running. Please make sure that import process is finished before starting new one.');
        }

        $params = [
            'channel' => $this->configuration->getChannel(),
            'quiet'   => 'true',
        ];

        foreach ($this->pushImportConfiguration->getImportTypes() as $type) {
            $this->client->post($this->getBaseEndpoint() . $type . '?' . http_build_query($params), [
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization' => $this->configuration->getAuthorization()
            ]);
        }

        return true;
    }

    /**
     * @return bool
     * @throws \Shopware\Components\HttpClient\RequestException
     */
    private function isRunning(): bool
    {
        $response = $this->client->get($this->getBaseEndpoint() . 'running?' . http_build_query(['channel' => $this->configuration->getChannel()]), [
            'Accept'        => 'application/json',
            'Authorization' => $this->configuration->getAuthorization()
        ]);

        return filter_var($response->getBody(), FILTER_VALIDATE_BOOLEAN);
    }

    private function getBaseEndpoint(): string
    {
        return $endpoint = $this->configuration->getServerUrl() . '/rest/v3/import/';
    }
}

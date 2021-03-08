<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Api\Credentials;
use Shopware\Components\HttpClient\HttpClientInterface;
use Shopware\Components\HttpClient\RequestException;

class TestConnectionService
{
    /** @var HttpClientInterface */
    private $client;

    /** @var string */
    private $apiQuery = 'FACT-Finder version';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->client = $httpClient;
    }

    /**
     * @throws RequestException
     */
    public function execute(string $url, string $channel, Credentials $credentials): void
    {
        $endpoint = sprintf('%s/rest/v4/search/%s', rtrim($url, '/'), $channel);
        $this->client->get($endpoint . '?' . http_build_query(['query' => $this->apiQuery]), [
            'Accept'        => 'application/json',
            'Authorization' => $credentials,
        ]);
    }
}

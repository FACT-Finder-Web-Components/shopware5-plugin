<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Api\Credentials;
use Shopware\Components\HttpClient\GuzzleHttpClient;

class TestConnectionService
{
    /** @var GuzzleHttpClient */
    private $client;

    /** @var string */
    private $apiQuery = 'FACT-Finder version';

    public function __construct(GuzzleHttpClient $httpClient)
    {
        $this->client = $httpClient;
    }

    /**
     * @param string $url
     * @param string $channel
     * @param Credentials $credentials
     *
     * @throws \Shopware\Components\HttpClient\RequestException
     */
    public function execute(string $url, string $channel, Credentials $credentials): void
    {
        $endpoint = rtrim($url, '/') . sprintf('/rest/v3/search/%s', $channel) . '?' . http_build_query(['query' => $this->apiQuery]);
        $this->client->get($endpoint, [
            'Accept' => 'application/json',
            'Authorization' => $credentials
        ]);
    }
}

<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use OmikronFactfinder\Components\Configuration;
use OmikronFactfinder\Components\FieldRoles\Mapper;
use Shopware\Components\HttpClient\HttpClientInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Components\Plugin\ConfigReader;
use Shopware\Components\Plugin\ConfigWriter;
use Shopware\Models\Plugin\Plugin;
use Shopware\Models\Shop\Shop;

class UpdateFieldRolesService
{
    private const FIELD_ROLES_CONFIG_NAME = 'ffFieldRoles';

    /** @var HttpClientInterface */
    private $client;

    /** @var Configuration */
    private $configuration;

    /** @var ConfigWriter */
    private $configWriter;

    /** @var ConfigReader */
    private $configReader;

    /** @var ModelManager */
    private $modelManager;

    /** @var string */
    private $pluginName;

    public function __construct(
        ConfigReader $configReader,
        ConfigWriter $configWriter,
        HttpClientInterface $httpClient,
        Configuration $configuration,
        ModelManager $modelManager,
        string $pluginName
    ) {
        $this->configReader  = $configReader;
        $this->client        = $httpClient;
        $this->configuration = $configuration;
        $this->configWriter  = $configWriter;
        $this->modelManager  = $modelManager;
        $this->pluginName    = $pluginName;
    }

    public function updateFieldRoles(): void
    {
        $shopRepository = $this->modelManager->getRepository(Shop::class);
        $shops          = $shopRepository->findAll();
        foreach ($shops as $shop) {
            $scopedConfig = $this->configReader->getByPluginName($this->pluginName, $shop);
            $endpoint     = $this->getEndpoint($this->configuration->getServerUrl(), $scopedConfig['ffChannel']);
            $response     = $this->client->get(
                "$endpoint?" . http_build_query(['query' => '*']),
                [
                    'Accept'        => 'application/json',
                    'Authorization' => $this->configuration->getCredentials()->__toString(),
                ]);

            $fieldRoles = json_decode($response->getBody(), true)['fieldRoles'];
            $plugin     = $this->getPlugin();
            $this->configWriter->saveConfigElement(
                $plugin,
                self::FIELD_ROLES_CONFIG_NAME,
                json_encode((new Mapper())->map($fieldRoles)),
                $shop
            );
        }
    }

    private function getEndpoint(string $url, string $channel): string
    {
        return sprintf('%s/rest/v4/search/%s', rtrim($url, '/'), $channel);
    }

    private function getPlugin(): Plugin
    {
        $pluginRepository = $this->modelManager->getRepository(Plugin::class);
        $plugin           = $pluginRepository->findOneBy(['name' => 'OmikronFactfinder']);

        if (!$plugin) {
            throw new \RuntimeException('OmikronFactfinder is not installed');
        }

        return $plugin;
    }
}

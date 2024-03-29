<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components;

use OmikronFactfinder\Components\Api\Credentials;

class Configuration
{
    /** @var array */
    private $pluginConfig;

    public function __construct(array $pluginConfig)
    {
        $this->pluginConfig = $pluginConfig;
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->pluginConfig['ffEnabled'] ?? false);
    }

    public function useForCategories(): bool
    {
        return $this->isEnabled() && ($this->pluginConfig['ffUseForCategories'] ?? false);
    }

    public function getServerUrl(): string
    {
        return rtrim($this->pluginConfig['ffServerUrl'] ?? '', ' /');
    }

    public function getChannel(): string
    {
        return $this->pluginConfig['ffChannel'] ?? '';
    }

    public function getApiVersion(): string
    {
        return $this->pluginConfig['ffApiVersion'] ?? 'v5';
    }

    public function getFieldRoles(): array
    {
        $default = '{"brand":"Brand","campaignProductNumber":"ProductNumber","deeplink":"Deeplink","description":"Description","displayProductNumber":"ProductNumber","ean":"EAN","imageUrl":"ImageUrl","masterArticleNumber":"Master","price":"Price","productName":"Name","trackingProductNumber":"ProductNumber"}';
        return json_decode($this->pluginConfig['ffFieldRoles'] ?? $default, true);
    }

    public function getCredentials(): Credentials
    {
        return new Credentials($this->pluginConfig['ffUser'] ?? '', $this->pluginConfig['ffPassword'] ?? '');
    }

    public function isFeatureEnabled(string $name): bool
    {
        return (bool) ($this->pluginConfig[$name] ?? false);
    }

    public function getTrackingSettings(): array
    {
        return [
            'addToCart' => [
                'count' => $this->pluginConfig['ffTrackingAddToCartCount'] ?? 'count_as_one',
            ],
        ];
    }
}

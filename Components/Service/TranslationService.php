<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware_Components_Translation as TranslationComponent;

class TranslationService
{
    private const ARTICLE_TRANSLATION         = 'article';
    private const CATEGORY_TRANSLATION        = 'category';
    private const PROPERTY_GROUP_TRANSLATION  = 'propertygroup';
    private const PROPERTY_OPTION_TRANSLATION = 'propertyoption';
    private const PROPERTY_VALUE_TRANSLATION  = 'propertyvalue';

    /** @var ContextServiceInterface */
    private $contextService;

    /** @var TranslationComponent */
    private $translationComponent;

    /** @var array */
    private $translationsInMemory = [
        self::ARTICLE_TRANSLATION         => [],
        self::CATEGORY_TRANSLATION        => [],
        self::PROPERTY_GROUP_TRANSLATION  => [],
        self::PROPERTY_OPTION_TRANSLATION => [],
        self::PROPERTY_VALUE_TRANSLATION  => [],
    ];

    public function __construct(ContextServiceInterface $contextService, TranslationComponent $translationComponent)
    {
        $this->translationComponent = $translationComponent;
        $this->contextService       = $contextService;
    }

    public function getArticleTranslation(int $articleId): array
    {
        return $this->get($articleId, self::ARTICLE_TRANSLATION);
    }

    public function getPropertyTranslation(int $groupId): array
    {
        return $this->get($groupId, self::PROPERTY_GROUP_TRANSLATION);
    }

    public function getPropertyOptionTranslation(int $optionId): array
    {
        return $this->get($optionId, self::PROPERTY_OPTION_TRANSLATION);
    }

    public function getPropertyValueTranslation(int $valueId): array
    {
        return $this->get($valueId, self::PROPERTY_VALUE_TRANSLATION);
    }

    public function getCategoryTranslation(int $categoryId): array
    {
        return $this->get($categoryId, self::CATEGORY_TRANSLATION);
    }

    private function getShopId(): int
    {
        return (int) $this->contextService->getShopContext()->getShop()->getId();
    }

    private function get(int $id, string $type): array
    {
        if (!isset($this->translationsInMemory[$type][$id])) {
            $translations = $this->translationComponent->read($this->getShopId(), $type, $id);

            $this->translationsInMemory[$type][$id] = $translations;
        }

        return $this->translationsInMemory[$type][$id];
    }
}

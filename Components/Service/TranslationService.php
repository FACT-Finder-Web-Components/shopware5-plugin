<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use Doctrine\DBAL\Connection;
use PDO;
use Shopware_Components_Translation as TranslationComponent;

class TranslationService
{
    private const ARTICLE            = 'article';
    private const VARIANT            = 'variant';
    private const CATEGORY           = 'category';
    private const PROPERTY_GROUP     = 'propertygroup';
    private const PROPERTY_OPTION    = 'propertyoption';
    private const PROPERTY_VALUE     = 'propertyvalue';
    private const CONFIGURATOR_GROUP = 'configuratorgroup';
    private const CONFIGURTOR_OPTION = 'configuratoroption';

    /** @var TranslationComponent */
    private $translationComponent;

    /** @var Connection */
    private $dbalConnection;

    /** @var array */
    private $translationsInMemory = [
        self::ARTICLE            => [],
        self::VARIANT            => [],
        self::CATEGORY           => [],
        self::PROPERTY_GROUP     => [],
        self::PROPERTY_OPTION    => [],
        self::PROPERTY_VALUE     => [],
        self::CONFIGURATOR_GROUP => [],
        self::CONFIGURTOR_OPTION => [],
    ];

    public function __construct(TranslationComponent $translationComponent, Connection $dbalConnection)
    {
        $this->translationComponent = $translationComponent;
        $this->dbalConnection       = $dbalConnection;
    }

    public function getArticleTranslation(int $articleId): array
    {
        return $this->get($articleId, self::ARTICLE);
    }

    public function getVariantTranslation(int $articleId): array
    {
        return $this->get($articleId, self::VARIANT);
    }

    public function getPropertyOptionTranslation(int $optionId): array
    {
        return $this->get($optionId, self::PROPERTY_OPTION);
    }

    public function getPropertyValueTranslation(int $valueId): array
    {
        return $this->get($valueId, self::PROPERTY_VALUE);
    }

    public function getConfiguratorGroupTranslation(int $groupId): array
    {
        return $this->get($groupId, self::CONFIGURATOR_GROUP);
    }

    public function getConfiguratorOptionTranslation(int $optionId): array
    {
        return $this->get($optionId, self::CONFIGURTOR_OPTION);
    }

    public function getCategoryTranslation(int $categoryId): array
    {
        return $this->get($categoryId, self::CATEGORY);
    }

    public function loadProductTranslations(int $shopId, array $productIds): void
    {
        $this->addToCache($this->translationComponent->readBatch($shopId, self::ARTICLE, $productIds));
    }

    public function loadVariantsTranslations(int $shopId, array $productIds)
    {
        $variantIds = $this->dbalConnection
            ->createQueryBuilder()
            ->select(['articleDetails.id'])
            ->from('s_articles_details', 'articleDetails')
            ->where('articleDetails.articleID IN (:ids)')
            ->setParameter(':ids', $productIds, Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll(PDO::FETCH_COLUMN);

        $this->addToCache($this->translationComponent->readBatch($shopId, self::VARIANT, $variantIds));
    }

    public function loadCategoriesTranslations(int $shopId, array $productIds): void
    {
        $categoryIds = $this->dbalConnection
            ->createQueryBuilder()
            ->select(['articleCategories.categoryID'])
            ->from('s_articles_categories_ro', 'articleCategories')
            ->where('articleCategories.articleID IN (:ids)')
            ->setParameter(':ids', $productIds, Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll(PDO::FETCH_COLUMN);

        $this->addToCache($this->translationComponent->readBatch($shopId, self::CATEGORY, $categoryIds));
    }

    public function loadPropertiesTranslations(int $shopId): void
    {
        $translationData = $this->dbalConnection
            ->createQueryBuilder()
            ->select(['objectdata, objectlanguage, objecttype, objectkey'])
            ->from('s_core_translations', 't')
            ->andWhere('t.objectlanguage = :objectLanguage')
            ->setParameter('objectLanguage', $shopId)
            ->andWhere('t.objecttype IN (:objectType)')
            ->setParameter('objectType', ['propertygroup', 'propertyoption', 'propertyvalue', 'configuratorgroup', 'configuratoroption'], Connection::PARAM_STR_ARRAY)
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        $this->addToCache($translationData, function (array $translation) {
            return $this->translationComponent->unFilterData($translation['objecttype'], $translation['objectdata']);
        });
    }

    private function get(int $id, string $type): array
    {
        return $this->translationsInMemory[$type][$id] ?? [];
    }

    private function addToCache(array $translations, callable $callback = null)
    {
        foreach ($translations as $translation) {
            $this->translationsInMemory[$translation['objecttype']][$translation['objectkey']] = (array) $callback ? $callback($translation) : $translation['objectdata'];
        }
    }
}

<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\ArticleAttribute;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use OmikronFactfinder\Components\Service\TranslationService;
use Shopware\Bundle\AttributeBundle\Service\ConfigurationStruct as AttributeConfig;
use Shopware\Bundle\AttributeBundle\Service\CrudService;
use Shopware_Components_Snippet_Manager as SnippetManager;

class SingleFields
{
    /** @var CrudService */
    private $crudService;

    /** @var SnippetManager */
    private $snippetManager;

    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var TranslationService */
    private $translationService;

    /** @var array */
    private $pluginConfig;

    /** @var ArticleAttribute[] */
    private $fields;

    public function __construct(
        CrudService $crudService,
        SnippetManager $snippetManager,
        NumberFormatter $numberFormatter,
        TranslationService $translationService,
        array $pluginConfig
    ) {
        $this->pluginConfig       = $pluginConfig;
        $this->crudService        = $crudService;
        $this->snippetManager     = $snippetManager;
        $this->numberFormatter    = $numberFormatter;
        $this->translationService = $translationService;
    }

    public function getFields(): array
    {
        $this->fields = $this->fields ?? array_map([$this, 'getField'], (array) $this->pluginConfig['ffSingleFields']);
        return $this->fields;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * Method is used in an old callback use expression [object, methodName] which is not detected by the md
     */
    private function getField(string $columnName): ArticleAttribute
    {
        $attribute = $this->crudService->get('s_articles_attributes', $columnName);
        return new ArticleAttribute(
            $attribute,
            $this->numberFormatter,
            $this->translationService,
            $this->snippetManager,
            $this->getLabel($attribute)
        );
    }

    private function getLabel(AttributeConfig $attribute): string
    {
        $key = sprintf('%s_%s_label', $attribute->getTableName(), $attribute->getColumnName());
        return $this->snippetManager->getNamespace('backend/attribute_columns')->get($key, $attribute->getLabel());
    }
}

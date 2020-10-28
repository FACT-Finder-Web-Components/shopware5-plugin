<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\ArticleAttribute;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
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

    /** @var array */
    private $pluginConfig;

    /** @var ArticleAttribute[] */
    private $fields;

    public function __construct(
        CrudService $crudService,
        SnippetManager $snippetManager,
        NumberFormatter $numberFormatter,
        array $pluginConfig
    ) {
        $this->pluginConfig    = $pluginConfig;
        $this->crudService     = $crudService;
        $this->snippetManager  = $snippetManager;
        $this->numberFormatter = $numberFormatter;
    }

    public function getFields(): array
    {
        $this->fields = $this->fields ?? array_map([$this, 'getField'], (array) $this->pluginConfig['ffSingleFields']);
        return $this->fields;
    }

    private function getField(string $columnName): ArticleAttribute
    {
        $attribute = $this->crudService->get('s_articles_attributes', $columnName);
        return new ArticleAttribute($attribute, $this->numberFormatter, $this->getLabel($attribute));
    }

    private function getLabel(AttributeConfig $attribute): string
    {
        $key = sprintf('%s_%s_label', $attribute->getTableName(), $attribute->getColumnName());
        return $this->snippetManager->getNamespace('backend/attribute_columns')->get($key, $attribute->getLabel());
    }
}

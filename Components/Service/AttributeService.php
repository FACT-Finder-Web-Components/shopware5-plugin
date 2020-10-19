<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Service;

use Shopware\Bundle\AttributeBundle\Service\ConfigurationStruct;
use Shopware\Bundle\AttributeBundle\Service\CrudServiceInterface;
use Shopware_Components_Snippet_Manager as SnippetManager;

class AttributeService
{
    /** @var CrudServiceInterface */
    private $crudService;

    /** @var SnippetManager */
    private $snippetManager;

    /** @var ConfigurationStruct[] */
    private $cachedAttributes;

    /** @var string[] */
    private $cachedLabels;

    public function __construct(CrudServiceInterface $crudService, SnippetManager $snippetManager)
    {
        $this->crudService    = $crudService;
        $this->snippetManager = $snippetManager;
    }

    public function getLabel(string $columnName): string
    {
        if (!isset($this->cachedLabels[$columnName])) {
            $column = $this->crudService->get('s_articles_attributes', $columnName);
            $key    = sprintf('%s_%s_label', $column->getTableName(), $column->getColumnName());

            $this->cachedLabels[$columnName] = $this->snippetManager->getNamespace('backend/attribute_columns')->get($key) ?? $column->getLabel();
        }
        return $this->cachedLabels[$columnName];
    }

    public function getAttribute(string $columnName): ConfigurationStruct
    {
        if (!isset($this->cachedAttributes[$columnName])) {
            $this->cachedAttributes[$columnName] = $this->crudService->get('s_articles_attributes', $columnName);
        }
        return $this->cachedAttributes[$columnName];
    }
}

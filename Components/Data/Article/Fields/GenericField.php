<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Filter\FilterInterface;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Bundle\AttributeBundle\Service\CrudServiceInterface;
use Shopware\Models\Article\Detail;
use Shopware_Components_Snippet_Manager as SnippetManager;

class GenericField implements FieldInterface
{
    /** @var CrudServiceInterface */
    private $crudService;

    /** @var SnippetManager */
    private $snippetManager;

    /** @var FilterInterface */
    private $filter;

    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var string */
    private $columnName = '';

    public function __construct(
        CrudServiceInterface $crudService,
        SnippetManager $snippetManager,
        FilterInterface $filter,
        NumberFormatter $numberFormatter
    ) {
        $this->crudService     = $crudService;
        $this->snippetManager  = $snippetManager;
        $this->filter          = $filter;
        $this->numberFormatter = $numberFormatter;
    }

    public function setColumnName(string $columnName)
    {
        $this->columnName = $columnName;
        return $this;
    }

    public function getName(): string
    {
        $column = $this->crudService->get('s_articles_attributes', $this->columnName);
        $key    = sprintf('%s_%s_label', $column->getTableName(), $column->getColumnName());
        return $this->snippetManager->getNamespace('backend/attribute_columns')->get($key) ?? $column->getLabel();
    }

    public function getValue(Detail $detail): string
    {
        $attributeObj  = $detail->getAttribute();
        $column        = $this->crudService->get('s_articles_attributes', $this->columnName);
        $getter        = 'get' . ucfirst($this->columnName);
        $valueReturned = method_exists($attributeObj, $getter) && $attributeObj->{"$getter"}() ? $attributeObj->{"$getter"}() : '';

        switch ($column->getColumnType()) {
            case 'multi_selection':
                return str_replace('|', '#', trim($valueReturned, '|'));
            case 'boolean':
                return $valueReturned ? 'Yes' : 'No';
            case 'datetime' && $valueReturned instanceof \DateTime:
                return $valueReturned->format('Y-m-d H:i:s');
            case 'float':
                return $this->numberFormatter->format((float) $valueReturned);
            default:
                return $valueReturned;
        }
    }
}

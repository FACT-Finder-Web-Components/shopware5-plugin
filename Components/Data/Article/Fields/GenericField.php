<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Filter\FilterInterface;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use OmikronFactfinder\Components\Service\AttributeService;
use Shopware\Models\Article\Detail;

class GenericField implements FieldInterface
{
    /** @var AttributeService */
    private $attributeService;

    /** @var FilterInterface */
    private $filter;

    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var string */
    private $columnName = '';

    public function __construct(
        AttributeService $attributeService,
        FilterInterface $filter,
        NumberFormatter $numberFormatter
    ) {
        $this->attributeService = $attributeService;
        $this->filter           = $filter;
        $this->numberFormatter  = $numberFormatter;
    }

    public function setColumnName(string $columnName)
    {
        $this->columnName = $columnName;
        return $this;
    }

    public function getName(): string
    {
        return $this->attributeService->getLabel($this->columnName);
    }

    public function getValue(Detail $detail): string
    {
        $attributeObj  = $detail->getAttribute();
        $column        = $this->attributeService->getAttribute($this->columnName);
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

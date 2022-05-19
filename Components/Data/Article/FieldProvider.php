<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\FieldInterface;

class FieldProvider
{
    /** @var FieldInterface[] */
    private $fields;

    /** @var SingleFields */
    private $singleFields;

    /** @var PriceCurrencyFields */
    private $priceCurrencyFields;

    /** @var string[] */
    private $columns;

    public function __construct(\Traversable $fields, SingleFields $singleFields, PriceCurrencyFields $priceCurrencyFields, array $columns)
    {
        $this->fields              = iterator_to_array($fields);
        $this->singleFields        = $singleFields;
        $this->columns             = $columns;
        $this->priceCurrencyFields = $priceCurrencyFields;
    }

    public function getColumns(): array
    {
        return array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $this->getFields())));
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * Method is used in an old callback use expression [object, methodName] which is not detected by the md
     */
    public function getFields(): array
    {
        return array_merge($this->fields, $this->singleFields->getFields(), $this->priceCurrencyFields->getPriceCurrencyFields());
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     * Method is used in an old callback use expression [object, methodName] which is not detected by the md
     */
    private function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }
}

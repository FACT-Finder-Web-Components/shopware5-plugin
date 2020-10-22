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

    /** @var string[] */
    private $columns;

    public function __construct(\Traversable $fields, SingleFields $singleFields, array $columns)
    {
        $this->fields       = iterator_to_array($fields);
        $this->singleFields = $singleFields;
        $this->columns      = $columns;
    }

    public function getColumns(): array
    {
        return array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $this->getFields())));
    }

    public function getFields(): array
    {
        return array_merge($this->fields, $this->singleFields->getFields());
    }

    private function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }
}

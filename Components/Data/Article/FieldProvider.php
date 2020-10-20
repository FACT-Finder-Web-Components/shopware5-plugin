<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\FieldInterface;

class FieldProvider
{
    /** @var FieldInterface[] */
    private $fields;

    /** @var string[] */
    private $columns;

    /** SingleFields */
    private $singleFields;

    public function __construct(\Traversable $fields, array $columns, SingleFields $singleFields)
    {
        $this->fields       = iterator_to_array($fields);
        $this->columns      = $columns;
        $this->singleFields = $singleFields;
    }

    public function getColumns(): array
    {
        return array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], array_merge($this->fields, $this->singleFields->getFields()))));
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

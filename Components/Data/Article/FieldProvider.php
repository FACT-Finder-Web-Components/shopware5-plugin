<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;

class FieldProvider
{
    /** @var ArticleFieldInterface[] */
    private $fields;

    /** @var string[] */
    private $columns;

    public function __construct(\Traversable $fields, array $columns)
    {
        $this->fields  = iterator_to_array($fields);
        $this->columns = $columns;
    }

    public function getColumns(): array
    {
        return array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], $this->fields)));
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    private function getFieldName(ArticleFieldInterface $field): string
    {
        return $field->getName();
    }
}

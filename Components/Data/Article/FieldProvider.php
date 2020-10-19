<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\FieldInterface;
use OmikronFactfinder\Components\Data\Article\Fields\GenericField;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FieldProvider implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var FieldInterface[] */
    private $fields;

    /** @var string[] */
    private $columns;

    /** @var array */
    private $pluginConfig;

    public function __construct(\Traversable $fields, array $columns, array $pluginConfig)
    {
        $this->fields       = iterator_to_array($fields);
        $this->columns      = $columns;
        $this->pluginConfig = $pluginConfig;
    }

    public function getColumns(): array
    {
        return array_unique(array_merge($this->columns, array_map([$this, 'getFieldName'], array_merge($this->fields, $this->getSingleFields()))));
    }

    public function getFields(): array
    {
        return $this->fields + $this->getSingleFields();
    }

    private function getSingleFields(): array
    {
        return array_map(function (string $columnName) {
            return (clone $this->container->get(GenericField::class))->setColumnName($columnName);
        }, (array) $this->pluginConfig['ffSingleFields']);
    }

    private function getFieldName(FieldInterface $field): string
    {
        return $field->getName();
    }
}

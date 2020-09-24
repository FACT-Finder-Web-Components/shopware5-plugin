<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Filter\TextFilter;
use Shopware\Models\Article\Detail;
use Shopware\Models\Property\Value;

class Attributes implements FieldInterface
{
    /** @var TextFilter */
    private $filter;

    public function __construct(TextFilter $filter)
    {
        $this->filter = $filter;
    }

    public function getName(): string
    {
        return 'Attributes';
    }

    public function getValue(Detail $detail): string
    {
        $attributes = array_reduce($detail->getArticle()->getPropertyValues()->toArray(), function (array $attrs, Value $value) {
            return $attrs + [$value->getId() => $this->formatAttribute($value->getOption()->getName(), $value->getValue())];
        }, []);

        return $attributes ? '|' . implode('|', array_values($attributes)) . '|' : '';
    }

    private function formatAttribute($name, $value): string
    {
        return "{$this->filter->filterValue($name)}={$this->filter->filterValue($value)}";
    }
}

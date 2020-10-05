<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Filter\FilterInterface;
use Shopware\Models\Article\Detail;
use Shopware\Models\Property\Value;

class Attributes implements FieldInterface
{
    /** @var FilterInterface */
    private $filter;

    public function __construct(FilterInterface $filter)
    {
        $this->filter = $filter;
    }

    public function getName(): string
    {
        return 'Attributes';
    }

    public function getValue(Detail $detail): string
    {
        $attributes = $detail->getArticle()->getPropertyValues()->map(function (Value $value) {
            return $this->format($value->getOption()->getName(), $value->getValue());
        });

        return count($attributes) ? '|' . implode('|', array_values($attributes->toArray())) . '|' : '';
    }

    private function format(string ...$parts): string
    {
        return implode('=', array_map([$this->filter, 'filterValue'], $parts));
    }
}

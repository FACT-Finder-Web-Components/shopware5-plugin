<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Filter\FilterInterface;
use Shopware\Models\Article\Configurator\Option;
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
        $properties = $detail->getArticle()->getPropertyValues()->map(function (Value $value) {
            return $this->format($value->getOption()->getName(), $value->getValue());
        })->toArray();

        $values = array_merge($properties, ...array_map([$this, 'getConfiguratorOptions'], $this->getDetails($detail)));
        return count($values) ? '|' . implode('|', array_unique($values)) . '|' : '';
    }

    private function getConfiguratorOptions(Detail $detail): array
    {
        return $detail->getConfiguratorOptions()->map(function (Option $option): string {
            return $this->format($option->getGroup()->getName(), $option->getName());
        })->toArray();
    }

    private function getDetails(Detail $detail): array
    {
        return $detail->getKind() === 1 ? $detail->getArticle()->getDetails()->toArray() : [$detail];
    }

    private function format(string ...$parts): string
    {
        return implode('=', array_map([$this->filter, 'filterValue'], $parts));
    }
}

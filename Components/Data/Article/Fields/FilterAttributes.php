<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Filter\FilterInterface;
use OmikronFactfinder\Components\Service\TranslationService;
use Shopware\Models\Article\Configurator\Option;
use Shopware\Models\Article\Detail;
use Shopware\Models\Property\Value;

class FilterAttributes implements FieldInterface
{
    /** @var FilterInterface */
    private $filter;

    /** @var TranslationService */
    private $translationService;

    public function __construct(FilterInterface $filter, TranslationService $translationService)
    {
        $this->filter             = $filter;
        $this->translationService = $translationService;
    }

    public function getName(): string
    {
        return 'FilterAttributes';
    }

    public function getValue(Detail $detail): string
    {
        $properties = $detail->getArticle()->getPropertyValues()->map(function (Value $value) {
            $valueTransl = $this->translationService->getPropertyValueTranslation($value->getId());
            $optionTransl = $this->translationService->getPropertyOptionTranslation($value->getOption()->getId());

            return $this->format($optionTransl['optionName'] ?: $value->getOption()->getName(), $valueTransl['optionValue'] ?: $value->getValue());
        })->toArray();

        $values = array_merge($properties, ...array_map([$this, 'getConfiguratorOptions'], $this->getDetails($detail)));

        return count($values) ? '|' . implode('|', array_unique($values)) . '|' : '';
    }

    private function getConfiguratorOptions(Detail $detail): array
    {
        return $detail->getConfiguratorOptions()->map(function (Option $option): string {
            $propertyTransl = $this->translationService->getPropertyTranslation($option->getGroup()->getId());
            $optionTransl = $this->translationService->getPropertyOptionTranslation($option->getId());

            return $this->format($propertyTransl['groupNam'] ?: $option->getGroup()->getName(), $optionTransl['optionName'] ?: $option->getName());
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

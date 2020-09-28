<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\ExportEntityInterface;
use Shopware\Models\Article\Detail;

class MainDetailProvider extends DetailProvider
{
    /** @var ArticleProviderFactory */
    private $providerFactory;

    public function __construct(ArticleProviderFactory $articleProviderFactory)
    {
        $this->providerFactory = $articleProviderFactory;
    }

    public function getId(): int
    {
        return (int) $this->article->getId();
    }

    public function toArray(): array
    {
        $data    = parent::toArray();
        $options = array_merge([], ...array_values($this->getConfigurableOptions()));
        if ($options) {
            $data['Attributes']  = ($data['Attributes'] ?: '|') . implode('|', array_unique($options)) . '|';
            $data['HasVariants'] = 1;
        }

        return $data;
    }

    public function getEntities(): iterable
    {
        yield from $this->article->getDetails()->map($this->articleVariant());
    }

    private function articleVariant(): callable
    {
        $options = $this->getConfigurableOptions();

        return function (Detail $variant) use ($options): ExportEntityInterface {
            return $this->providerFactory->create($variant, ['Attributes' => '|' . implode('|', $options[$variant->getNumber()] ?? []) . '|']);
        };
    }

    private function getConfigurableOptions(): array
    {
        return array_reduce($this->article->getDetails()->toArray(), function (array $attributes, Detail $detail) {
            return $attributes + [
                    $detail->getNumber() => array_map(function ($value) {
                        return "{$this->filter->filterValue($value->getGroup()->getName())}={$this->filter->filterValue($value->getName())}";
                    }, $detail->getConfiguratorOptions()->getValues()),
                ];
        }, []);
    }
}

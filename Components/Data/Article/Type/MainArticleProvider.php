<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use IteratorAggregate;
use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use Shopware\Models\Article\Detail;

class MainArticleProvider extends BaseArticle implements DataProviderInterface
{
    /** @var ArticleProviderFactory */
    private $providerFactory;

    /** @var IteratorAggregate */
    private $articleFields;

    public function __construct(ArticleProviderFactory $articleProviderFactory, IteratorAggregate $articleFields)
    {
        $this->providerFactory = $articleProviderFactory;
        $this->articleFields   = $articleFields;
    }

    public function getId(): int
    {
        return (int)$this->article->getId();
    }

    public function toArray(): array
    {
        $data = array_reduce(iterator_to_array($this->articleFields), function (array $fields, ArticleFieldInterface $field) {
            return $fields + [$field->getName() => $field->getValue($this->article)];
        }, parent::toArray());

        $options = array_merge([], ...array_values($this->getConfigurableOptions()));
        if ($options) {
            $data = ['Attributes' => ($data['Attributes'] ?? '|') . implode('|', array_unique($options)) . '|'] + $data;
        }

        return $data;
    }

    public function getEntities(): iterable
    {
        yield from [$this];
        yield from array_map($this->articleVariant(), array_filter($this->article->getDetails()->toArray(), function (Detail $detail) {
            return $detail->getNumber() !== $detail->getArticle()->getMainDetail()->getNumber();
        }));
    }

    private function articleVariant(): callable
    {
        $options = $this->getConfigurableOptions();

        return function (Detail $variant) use ($options): ExportEntityInterface {
            //@todo don't pass data as additional argument?
            return $this->providerFactory->create($variant, ['Attributes' => '|' . implode('|', $options[$variant->getNumber()] ?? []) . '|']);
        };
    }

    private function getConfigurableOptions(): array
    {
        return array_reduce($this->article->getDetails()->toArray(), function (array $attributes, Detail $detail) {
            return $attributes + [$detail->getNumber() => array_map(function ($value) {
                    return "{$this->filter->filterValue($value->getGroup()->getName())}={$this->filter->filterValue($value->getName())}";
                }, $detail->getConfiguratorOptions()->getValues())
                ];
        }, []);
    }
}
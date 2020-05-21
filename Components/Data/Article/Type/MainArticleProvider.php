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
    /** @var ArticleProviderFactory  */
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
        return (int) $this->article->getId();
    }

    public function toArray(): array
    {
        $data = array_reduce(iterator_to_array($this->articleFields), function (array $fields, ArticleFieldInterface $field) {
            $fields[$field->getName()] = $field->getValue($this->article);
            return $fields;
        }, parent::toArray());

        $options = array_merge([], ...array_values($this->getConfigurableOptions()));
        if ($options) {
            $data = ['Attributes' => ($data['Attributes'] ?? '|') . implode('|', $options) . '|'] + $data;
        }

        return $data;
    }

    public function getEntities(): iterable
    {
        yield from array_map($this->articleVariant(), $this->article->getDetails()->toArray());
    }

    private function articleVariant(): callable
    {
        $options = $this->getConfigurableOptions();

        return function (Detail $variant) use ($options): ExportEntityInterface {
            if ($variant->getNumber() === $this->article->getMainDetail()->getNumber()) {
                return $this;
            }
            //@todo don't pass data as additional argument?
            return $this->providerFactory->create($variant, ['Attributes' => '|' . implode('|', $options[$variant->getNumber()] ?? []) . '|']);
        };
    }

    private function getConfigurableOptions()
    {
        return array_reduce($this->article->getDetails()->toArray(), function (array $attributes, Detail $detail) {
            foreach ($detail->getConfiguratorOptions()->getValues() as $value) {
                $attributes[$detail->getNumber()][] = "{$this->filter->filterValue($value->getGroup()->getName())}={$this->filter->filterValue($value->getName())}";
            }
            return $attributes;
        }, []);
    }
}

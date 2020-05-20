<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use IteratorAggregate;
use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Filter\TextFilter;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Configurator\Option;
use Shopware\Models\Article\Detail;

class MainArticle extends BaseArticle implements DataProviderInterface
{
    private const  MAIN_ARTICLE_KIND = 1;

    /** @var VariantFactory */
    private $variantFactory;

    /** @var IteratorAggregate */
    protected $articleFields;

    public function __construct(
        Article $article,
        NumberFormatter $numberFormatter,
        VariantFactory $variantFactory,
        TextFilter $textFilter,
        IteratorAggregate $articleFields
    ) {
        parent::__construct($article, $numberFormatter, $textFilter);

        $this->variantFactory = $variantFactory;
        $this->articleFields  = $articleFields;
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

        $options = array_merge([], ...array_values( $this->getConfigurableOptions()));
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

        return function (Detail $variant) use ($options) : ExportEntityInterface {
            if ($variant->getKind() == self::MAIN_ARTICLE_KIND) {
                return $this;
            }
            return $this->variantFactory->create(
                $variant,
                ['Attributes' => '|' . implode('|', $options[$variant->getNumber()] ?? []) . '|']
            );
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

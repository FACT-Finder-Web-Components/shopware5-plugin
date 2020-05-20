<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use IteratorAggregate;
use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class MainArticle implements DataProviderInterface, ExportEntityInterface
{
    private const  MAIN_ARTICLE_KIND = 1;

    /** @var Article */
    protected $article;

    /** @var NumberFormatter */
    protected $numberFormatter;

    /** @var IteratorAggregate */
    protected $articleFields;

    /** @var VariantFactory */
    private $variantFactory;

    public function __construct(Article $article, VariantFactory $variantFactory, NumberFormatter $numberFormatter, IteratorAggregate $articleFields)
    {
        $this->article         = $article;
        $this->variantFactory  = $variantFactory;
        $this->numberFormatter = $numberFormatter;
        $this->articleFields   = $articleFields;
    }

    public function getId(): int
    {
        return (int) $this->article->getId();
    }

    public function toArray(): array
    {
        $data = [
            'ProductNumber' => (string) $this->article->getMainDetail()->getNumber(),
            'Master'        => (string) $this->article->getMainDetail()->getNumber(),
            'Name'          => (string) $this->article->getName(),
            'EAN'           => (string) $this->article->getMainDetail()->getEan(),
            'Weight'        => (float) $this->article->getMainDetail()->getWeight(),
            'Description'   => (string) $this->article->getDescriptionLong(),
            'Short'         => (string) $this->article->getDescription(),
            'Price'         => $this->numberFormatter->format((float) $this->article->getMainDetail()->getPrices()[0]->getPrice()),
            'Brand'         => (string) $this->article->getSupplier()->getName(),
            'Availability'  => (int) $this->article->getMainDetail()->getActive(),
            'HasVariants'   => $this->article->getDetails()->count() ? 1 : 0,
            'ShopwareId'    => (string) $this->article->getId(),
        ];

        return array_reduce(iterator_to_array($this->articleFields), function (array $fields, ArticleFieldInterface $field) {
            $fields[$field->getName()] = $field->getValue($this->article);
            return $fields;
        }, $data);
    }

    public function getEntities(): iterable
    {
        yield from array_map($this->articleVariant(), $this->article->getDetails()->toArray());
    }

    private function articleVariant(): callable
    {
        $data = $this->toArray();
        return function (Detail $variant) use ($data): ExportEntityInterface {
            if (self::MAIN_ARTICLE_KIND == $variant->getKind()) {
                return $this;
            }
            return $this->variantFactory->create($variant, $data);
        };
    }

    //@todo poc how to obtain configurable attributes
    private function getConfigurableAttributes()
    {
        $groups = [];
        foreach ($this->article->getDetails() as $detail) {
            foreach ($detail->getConfiguratorOptions() as $option) {
                $groups[$option->getGroup()->getName()][] = $option->getName();
            }
        }
        return $groups;
    }
}

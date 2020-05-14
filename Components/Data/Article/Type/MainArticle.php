<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class MainArticle implements DataProviderInterface, ExportEntityInterface
{
    /** @var Article */
    protected $article;

    /** @var VariantFactory */
    private $variantFactory;

    /** @var NumberFormatter */
    protected $numberFormatter;

    /** @var array */
    protected $articleFields;

    public function __construct(Article $article, VariantFactory $variantFactory, NumberFormatter $numberFormatter, array $articleFields = [])
    {
        $this->article = $article;
        $this->variantFactory = $variantFactory;
        $this->numberFormatter = $numberFormatter;
        $this->articleFields = $articleFields;
    }

    public function getId(): int
    {
        return (int)$this->article->getId();
    }

    public function toArray(): array
    {
        $data = [
            'ProductNumber' => (string)$this->article->getMainDetail()->getArticleId(),
            'Master' => (string)$this->article->getMainDetail()->getNumber(),
            'Name' => (string)$this->article->getName(),
            'Description' => (string)$this->article->getDescriptionLong(),
            'Short' => (string)$this->article->getDescription(),
            'ProductURL' => (string)'', //@todo
            'Price' => $this->numberFormatter->format((float)$this->article->getMainDetail()->getPrices()[0]->getPrice()),
            'Brand' => (string)$this->article->getSupplier()->getName(),
            'Availability' => (int)$this->article->getMainDetail()->getActive(),
            'HasVariants' => $this->article->getDetails()->count() ? 1 : 0,
            'ShopwareId' => (string)$this->article->getId(),
        ];

        return array_merge($data, array_map(function (ArticleFieldInterface $field): string {
            return $field->getValue($this->article);
        }, $this->articleFields));

    }

    public function getEntities(): iterable
    {
        yield from [$this];
        //@todo yield from getVariants()
        yield from array_map($this->articleVariant(), $this->article->getDetails());
    }

    private function articleVariant(): callable
    {
        $data = $this->toArray();
        return function (Detail $variant) use ($data) : ExportEntityInterface {
            return $this->variantFactory->create($variant, $data);
        };
    }
}

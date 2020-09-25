<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\Article\FieldProvider;
use OmikronFactfinder\Components\Data\Article\Fields\FieldInterface;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Filter\ExtendedTextFilter;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class DetailProvider implements ExportEntityInterface, DataProviderInterface
{
    /** @var Article */
    protected $article;

    /** @var DetailProvider */
    protected $detail;

    /** @var ExtendedTextFilter */
    protected $filter;

    /** @var FieldProvider */
    protected $fieldProvider;

    public function setDetail(Detail $detail)
    {
        $this->detail = $detail;
    }

    public function setArticle(Article $article)
    {
        $this->article = $article;
    }

    public function setTextFilter(ExtendedTextFilter $textFilter)
    {
        $this->filter = $textFilter;
    }

    public function setFieldProvider(FieldProvider $fieldProvider)
    {
        $this->fieldProvider = $fieldProvider;
    }

    public function getId(): int
    {
        return (int) $this->detail->getId();
    }

    public function toArray(): array
    {
        $baseData = [
            'ProductNumber' => (string) $this->detail->getNumber(),
            'Master'        => (string) $this->article->getMainDetail()->getNumber(),
            'Name'          => (string) $this->article->getName(),
            'EAN'           => (string) $this->detail->getEan(),
            'Weight'        => (float) $this->detail->getWeight(),
            'Description'   => (string) $this->article->getDescriptionLong(),
            'Short'         => (string) $this->article->getDescription(),
            'Brand'         => (string) $this->article->getSupplier()->getName(),
            'Availability'  => (int) $this->detail->getActive(),
            'HasVariants'   => 0,
        ];

        return array_reduce($this->fieldProvider->getFields(), function (array $fields, FieldInterface $field) {
            return $fields + [$field->getName() => $field->getValue($this->detail)];
        }, $baseData);

    }

    public function getEntities(): iterable
    {
        return [$this];
    }
}
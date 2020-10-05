<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\Article\FieldProvider;
use OmikronFactfinder\Components\Data\Article\Fields\FieldInterface;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Filter\ExtendedTextFilter;
use Shopware\Models\Article\Detail;

class DetailProvider implements ExportEntityInterface, DataProviderInterface
{
    /** @var ExtendedTextFilter */
    protected $filter;

    /** @var FieldProvider */
    protected $fieldProvider;

    /** @var ProviderFactory */
    protected $providerFactory;

    /** @var Detail */
    protected $detail;

    /** @var array */
    protected $data = [];

    public function __construct(
        ExtendedTextFilter $filter,
        FieldProvider $fieldProvider,
        ProviderFactory $providerFactory
    ) {
        $this->filter          = $filter;
        $this->fieldProvider   = $fieldProvider;
        $this->providerFactory = $providerFactory;
    }

    public function setDetail(Detail $detail)
    {
        $this->detail = $detail;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getId(): int
    {
        return (int) $this->detail->getId();
    }

    public function toArray(): array
    {
        $article = $this->detail->getArticle();

        $data = [
            'ProductNumber' => (string) $this->detail->getNumber(),
            'Master'        => (string) $article->getMainDetail()->getNumber(),
            'Name'          => (string) $article->getName(),
            'EAN'           => (string) $this->detail->getEan(),
            'Weight'        => (float) $this->detail->getWeight(),
            'Description'   => (string) $article->getDescriptionLong(),
            'Short'         => (string) $article->getDescription(),
            'Brand'         => (string) $article->getSupplier()->getName(),
            'HasVariants'   => $this->detail->getKind() === 1 && count($article->getDetails()) > 1 ? 1 : 0,
        ];

        return array_reduce($this->fieldProvider->getFields(), function (array $fields, FieldInterface $field) {
            return $fields + [$field->getName() => $field->getValue($this->detail)];
        }, $data + $this->data);
    }

    public function getEntities(): iterable
    {
        return [$this];
    }
}

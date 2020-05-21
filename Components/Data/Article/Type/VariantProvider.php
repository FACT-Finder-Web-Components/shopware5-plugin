<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\DataProviderInterface;

class VariantProvider extends BaseArticle implements DataProviderInterface
{
    /** @var array */
    private $data;

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
        return [
                'ProductNumber' => (string) $this->detail->getNumber(),
                'Availability'  => (int) $this->detail->getActive(),
                'ShopwareId'    => (string) $this->detail->getArticleId(),
            ] + $this->data + parent::toArray();
    }

    public function getEntities(): iterable
    {
        return [$this];
    }
}

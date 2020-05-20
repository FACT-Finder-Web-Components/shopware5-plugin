<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Filter\TextFilter;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Detail;
use Shopware\Models\Article\Article;

class Variant extends BaseArticle implements DataProviderInterface
{
    /** @var Detail */
    private $detail;

    /** @var array */
    private $data;

    public function __construct(
        NumberFormatter $numberFormatter,
        TextFilter $textFilter,
        Article $article,
        Detail $detail,
        array $data
    ) {
        parent::__construct($article, $numberFormatter, $textFilter);

        $this->detail  = $detail;
        $this->data    = $data;
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
                'HasVariants'   => 0,
                'ShopwareId'    => (string) $this->detail->getArticleId(),
            ] + $this->data + parent::toArray();
    }

    public function getEntities(): iterable
    {
        return [$this];
    }
}

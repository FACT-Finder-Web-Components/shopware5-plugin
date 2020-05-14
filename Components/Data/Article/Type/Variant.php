<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\Article\Fields\ArticleFieldInterface;
use OmikronFactfinder\Components\Data\DataProviderInterface;
use OmikronFactfinder\Components\Data\ExportEntityInterface;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class Variant implements ExportEntityInterface, DataProviderInterface
{
    /** @var Detail */
    private $detail;

    /** @var Article */
    private $article;

    /** @var array */
    private $data;

    /** @var NumberFormatter */
    private $numberFormatter;

    public function __construct(
        Detail $detail,
        array $data,
        NumberFormatter $numberFormatter
    )
    {
        $this->detail = $detail;
        $this->article = $this->detail->getArticle();
        $this->data = $data;
        $this->numberFormatter = $numberFormatter;
    }

    public function getId(): int
    {
        // TODO: Implement getId() method.
    }

    public function toArray(): array
    {
        return [
                'ProductNumber' => (string)$this->detail->getNumber(),
                'Availability' => (int)$this->detail->getActive(),
                'HasVariants' => 0,
                'ShopwareId' => (string)$this->detail->getArticleId(),
            ] + $this->data;
    }

    public function getEntities(): iterable
    {
        return [$this];
    }
}

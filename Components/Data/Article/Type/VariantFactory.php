<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Filter\TextFilter;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Detail;

class VariantFactory
{
    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var TextFilter */
    private $filter;

    public function __construct(NumberFormatter $numberFormatter, TextFilter $textFilter)
    {
        $this->numberFormatter = $numberFormatter;
        $this->filter          = $textFilter;
    }

    public function create(Detail $detail, array $data = [])
    {
        return new Variant($this->numberFormatter, $this->filter, $detail->getArticle(), $detail, $data);
    }
}

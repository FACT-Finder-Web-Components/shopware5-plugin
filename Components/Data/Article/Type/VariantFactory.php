<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class VariantFactory
{
    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var array */
    private $articleFields;

    public function __construct(NumberFormatter $numberFormatter, array $articleFields = [])
    {
        $this->numberFormatter = $numberFormatter;
        $this->articleFields = $articleFields;
    }

    public function create(Detail $detail, array $data = [])
    {
        return new Variant($detail, $data, $this->numberFormatter, $this->articleFields);
    }
}

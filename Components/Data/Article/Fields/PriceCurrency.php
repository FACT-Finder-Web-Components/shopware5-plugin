<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Article\Detail;
use Shopware\Models\Shop\Currency;

class PriceCurrency extends Price
{
    /** @var Currency */
    private $currency;

    /** @var string */
    private $fieldName;

    public function __construct(NumberFormatter $numberFormatter, Currency $currency, string $fieldName)
    {
        parent::__construct($numberFormatter);
        $this->currency  = $currency;
        $this->fieldName = $fieldName;
    }


    public function getName(): string
    {
        return $this->fieldName;
    }

    public function getValue(Detail $detail): string
    {
        return (string) $this->numberFormatter->format(parent::getValue($detail) * $this->currency->getFactor());
    }
}

<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\PriceCurrency;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Models\Shop\Currency;

class PriceCurrencyFields
{
    /** @var NumberFormatter  */
    private $numberFormatter;

    /** @var array  */
    private $fieldRoles;

    public function __construct(

        NumberFormatter $numberFormatter,
        array $fieldRoles
    ) {
        $this->numberFormatter = $numberFormatter;
        $this->fieldRoles      = $fieldRoles;
    }

    public function getPriceCurrencyFields(): array
    {
        return array_merge([], ...array_map(function (Currency $currency): array {
            $colName = sprintf('%s_%s', $this->fieldRoles['price'], $currency->getName());
            return [$colName => new PriceCurrency($this->numberFormatter, $currency, $colName)];
        }, Shopware()->Models()->getRepository(Currency::class)->findAll()));
    }
}

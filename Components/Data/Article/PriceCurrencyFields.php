<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use OmikronFactfinder\Components\Data\Article\Fields\PriceCurrency;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Shop\Currency;

class PriceCurrencyFields
{
    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var ModelManager */
    private $modelManager;

    /** @var array */
    private $fieldRoles;

    public function __construct(
        NumberFormatter $numberFormatter,
        ModelManager $modelManager,
        array $fieldRoles
    ) {
        $this->numberFormatter = $numberFormatter;
        $this->modelManager    = $modelManager;
        $this->fieldRoles      = $fieldRoles;
    }

    public function getPriceCurrencyFields(): array
    {
        return array_merge([], ...array_map(function (Currency $currency): array {
            $colName = sprintf('%s_%s', $this->fieldRoles['price'], $currency->getName());
            return [$colName => new PriceCurrency($this->numberFormatter, $currency, $colName)];
        }, $this->modelManager->getRepository(Currency::class)->findAll()));
    }
}

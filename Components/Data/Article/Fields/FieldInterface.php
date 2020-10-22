<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Fields;

use Shopware\Models\Article\Detail;

interface FieldInterface
{
    public function getName(): string;

    public function getValue(Detail $detail): string;
}

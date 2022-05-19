<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data;

interface DataProviderInterface
{
    /**
     * @return ExportEntityInterface[]
     */
    public function getEntities(): iterable;
}

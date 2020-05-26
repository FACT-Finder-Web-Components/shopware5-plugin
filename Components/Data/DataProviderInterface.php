<?php

namespace OmikronFactfinder\Components\Data;

interface DataProviderInterface
{
    /**
     * @return ExportEntityInterface[]
     */
    public function getEntities(): iterable;
}

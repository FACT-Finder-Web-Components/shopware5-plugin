<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Output;

interface StreamInterface
{
    public function addEntity(array $entity): void;

    public function getContent(): string;
}

<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Output;

class Csv implements StreamInterface
{
    public function addEntity(array $entity): void
    {
        dump($entity);
    }

    public function getContent(): string
    {
        throw new \BadMethodCallException('Not implemented');
    }
}
